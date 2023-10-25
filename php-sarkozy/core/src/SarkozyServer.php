<?php

namespace PhpSarkozy\core;

use PhpSarkozy\core\utils;
use PhpSarkozy\core\attributes\SarkozyModule;
use PhpSarkozy\core\utils\ModuleUtils;

class SarkozyServer
{

    /**
     * @var \ReflectionClass[]
     */
    private array $controllers;

    /**
     * @var \ReflectionClass[]
     */
    private array $module_classes;

    private array $modules = array();

    private array $middlewares = array();

    private int $port;
  
    private $host = 'localhost';

    private $protocol = 'tcp';
    private ?string $views_path;

    function __construct(int $port = 2007, string $views_path = null)
    {
        $this->port = $port;
        $this->views_path = $views_path;
        if($views_path === null){
            $this->views_path = getcwd()."/views";
        }
    }

    /**
     * Runs the server
     *
     * @return void
     **/
    public function run()
    {
        $this->controllers = utils\ControllerUtils::get_all_controllers();
        $this->module_classes = utils\ModuleUtils::get_all_modules();
        $this->middlewares = utils\MiddlewareUtils::get_all_middlewares();

        $this->init_modules();
        echo "Modules initialized\r\n";
        utils\LogUtils::echo_welcome($this->get_welcome_url());
        $this->listen();
    }

    private function get_welcome_url(){
        $protocol_class = $this->module_classes[SarkozyModule::PROTOCOL_MODULE];
        $protocol = $this->protocol;
        if ($protocol_class->hasMethod("get_protocol")){
            $protocol = $this->modules[SarkozyModule::PROTOCOL_MODULE]->get_protocol();
        }
        return "$protocol://$this->host:$this->port";
    }


    private function init_modules()
    {
        $this->init_single_module(SarkozyModule::TEMPLATE_MODULE, ["path" => $this->views_path]);

        $this->init_single_module(SarkozyModule::ROUTING_MODULE, []);

        //Protocol Module
        $this->init_single_module(SarkozyModule::PROTOCOL_MODULE, ["controllers" => $this->controllers, "modules" => $this->modules]);

        //Middlewares 
        $this->init_single_module(SarkozyModule::MIDDLEWARE_MODULE, ["middlewares" => $this->middlewares]);

        ModuleUtils::check_modules($this->module_classes);
    }

    private function init_single_module(int $module_flag, array $args){
        if( !key_exists($module_flag, $this->module_classes) ){
            $this->modules[$module_flag] = null;
            return;
        }
        $protocol_module_class = $this->module_classes[$module_flag];
        $this->modules[$module_flag] = $protocol_module_class
            ->newInstance(...$args);
    }

    private function get_return_value(api\Request $request){
        /**
         * @var api\SarkontrollerRequest $call
         */
        $call = $this->modules[SarkozyModule::PROTOCOL_MODULE]->get_call($request);
        $request->call = $call;

        $called_controller = $this->controllers[
            $call->controller_index
        ];

        if(!$called_controller->hasMethod($call->controller_method)){
            $error_message = "A problem occurred during controller method resolution";
            echo $error_message."\n";
            throw new \Exception($error_message);
        }

        $controller_instance = $called_controller->newInstance();
        $controller_method = $called_controller->getMethod($call->controller_method);
        return $controller_method->invokeArgs($controller_instance, $call->args);
    }

    private function listen(){
        $protocol_module = $this->modules[SarkozyModule::PROTOCOL_MODULE];

        $server = stream_socket_server("$this->protocol://$this->host:$this->port", $errno, $errstr);
        if (!$server) {
            die("Runtime error : server failed to start $errstr ($errno)\n");
        }
        
        while ($client = stream_socket_accept($server, -1)) {
            /**
             * @var api\Request $request
             */
            $request = $protocol_module->get_request($client);

            $middleware_data = null;

            try{
                // Request interception 
                $middleware_data = utils\MiddlewareUtils::intercept_request($request,$this->modules);
            
                $controller_return = $this->get_return_value($request);
            }catch(\Exception $e){
                $controller_return = $e;
            }

            try{
                $request = $protocol_module->handle_response($request, $controller_return);
            }catch(\Exception $e){
                $request = $protocol_module->handle_response($request, $e);
            }

            // Response interception 
            utils\MiddlewareUtils::intercept_response($request,$this->modules, $middleware_data);

            $raw_response = $protocol_module->get_raw_response($request);
            fwrite($client, $raw_response);
            fclose($client);
        }
        
        fclose($server);
    }
}



?>