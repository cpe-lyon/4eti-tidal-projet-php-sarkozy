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
    private array $moduleClasses;

    private array $modules = array();

    private int $port;
  
    private $host = 'localhost';

    private $protocol = 'tcp';
    private ?string $viewsPath;

    function __construct(int $port = 2007, string $viewsPath = null)
    {
        $this->port = $port;
        $this->viewsPath = $viewsPath;
        if($viewsPath === null){
            $this->viewsPath = getcwd()."/views";
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
        $this->moduleClasses = utils\ModuleUtils::get_all_modules();
        $this->init_modules();
        echo "Modules initialized\r\n";
        utils\LogUtils::echo_welcome($this->get_welcome_url());
        $this->listen();
    }

    private function get_welcome_url(){
        $protocolClass = $this->moduleClasses[SarkozyModule::PROTOCOL_MODULE];
        $protocol = $this->protocol;
        if ($protocolClass->hasMethod("get_protocol")){
            $protocol = $this->modules[SarkozyModule::PROTOCOL_MODULE]->get_protocol();
        }
        return "$protocol://$this->host:$this->port";
    }


    //TODO @josse.de-oliveira: check modules definition
    private function init_modules()
    {
        $this->init_single_module(SarkozyModule::TEMPLATE_MODULE, ["path" => $this->viewsPath]);

        $this->init_single_module(SarkozyModule::ROUTING_MODULE, []);

        //Protocol Module
        $this->init_single_module(SarkozyModule::PROTOCOL_MODULE, ["controllers" => $this->controllers, "modules" => $this->modules]);

        ModuleUtils::check_modules($this->moduleClasses);
    }

    private function init_single_module(int $module_flag, array $args){
        if( !key_exists($module_flag, $this->moduleClasses) ){
            $this->modules[$module_flag] = null;
            return;
        }
        $protocolModuleClass = $this->moduleClasses[$module_flag];
        $this->modules[$module_flag] = $protocolModuleClass
            ->newInstance(...$args);
    }

    private function get_return_value(api\Request $request){
        /**
         * @var api\SarkontrollerRequest $call
         */
        $call = $this->modules[SarkozyModule::PROTOCOL_MODULE]->get_call($request);
        $request->call = $call;

        $called_controller = $this->controllers[
            $call->controllerIndex
        ];

        if(!$called_controller->hasMethod($call->controllerMethod)){
            $error_message = "A problem occurred during controller method resolution";
            echo $error_message."\n";
            throw new \Exception($error_message);
        }

        $controller_instance = $called_controller->newInstance();
        $controller_method = $called_controller->getMethod($call->controllerMethod);
        return $controller_method->invokeArgs($controller_instance, $call->args);
    }

    private function listen(){
        $protocol_module = $this->modules[SarkozyModule::PROTOCOL_MODULE];

        $server = stream_socket_server("$this->protocol://$this->host:$this->port", $errno, $errstr);
        if (!$server) {
            die("Runtime error : server failed to start $errstr ($errno)\n");
        }

        pcntl_signal(SIGINT, function () use ($server) {
            fclose($server);
            echo "Serveur arrêté.\n";
            exit();
        });
        
        while ($client = stream_socket_accept($server, -1)) {
            /**
             * @var api\Request $request
             */
            $request = $protocol_module->get_request($client);

            try{
                $controllerReturn = $this->get_return_value($request);
            }catch(\Exception $e){
                $controllerReturn = $e;
            }

            try{
                $request = $protocol_module->handle_response($request, $controllerReturn);
            }catch(\Exception $e){
                $request = $protocol_module->handle_response($request, $e);
            }

            $raw_response = $protocol_module->get_raw_response($request);
            fwrite($client, $raw_response);
            fclose($client);
        }
        
        fclose($server);
    }
}



?>