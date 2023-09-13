<?php

namespace PhpSarkozy\core;

use PhpSarkozy\core\api\SarkoError;
use PhpSarkozy\core\attributes\SarkozyModule;

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

    function __construct(int $port = 2007)
    {
        $this->port = $port;
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
        $this->listen();
    }


    //TODO @josse.de-oliveira: check modules definition
    private function init_modules()
    {
        //HTTP Module
        $this->init_single_module(SarkozyModule::HTTP_MODULE, ["controllers" => $this->controllers, "modules" => $this->modules]);
    }

    private function init_single_module(int $module_flag, array $args){
        if( !key_exists($module_flag, $this->moduleClasses) ){
            $this->modules[$module_flag] = null;
            return;
        }
        $httpModuleClass = $this->moduleClasses[$module_flag];
        $this->modules[$module_flag] = $httpModuleClass
            ->newInstance(...$args);
    }

    private function get_return_value(api\Request $request){
        /**
         * @var api\SarkontrollerRequest $call
         */
        $call = $this->modules[SarkozyModule::HTTP_MODULE]->get_call($request);

        $called_controller = $this->controllers[
            $call->controllerIndex
        ];

        if(!$called_controller->hasMethod($call->controllerMethod)){
            //TODO @theo.clere Error parsing
            return new SarkoError(SarkoError::CANT_CALL_CONTROLLER);
        }

        $controller_instance = $called_controller->newInstance();
        $controller_method = $called_controller->getMethod($call->controllerMethod);
        return $controller_method->invokeArgs($controller_instance, $call->args);
    }

    private function listen(){
        $host = 'localhost';
        $port = $this->port;
        $http_module = $this->modules[SarkozyModule::HTTP_MODULE];

        $server = stream_socket_server("tcp://$host:$port", $errno, $errstr);
        if (!$server) {
            die("Runtime error : server failed to start $errstr ($errno)\n");
        }
        
        while ($client = stream_socket_accept($server, -1)) {
            /**
             * @var api\Request $request
             */
            $request = $http_module->get_request($client);

            $controllerReturn = $this->get_return_value($request);

            $request = $http_module->handle_response($request, $controllerReturn);

            $raw_response = $http_module->get_raw_response($request);
            fwrite($client, $raw_response);
            fclose($client);
        }
        
        fclose($server);
    }
}



?>