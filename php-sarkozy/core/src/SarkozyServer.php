<?php

namespace PhpSarkozy\core;

use PhpSarkozy\core\attributes\SarkozyModule;

class SarkozyServer
{

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


    //TODO: check modules definition
    private function init_modules()
    {
        //HTTP Module
        $this->init_single_module(SarkozyModule::HTTP_MODULE, ["controllers" => $this->controllers, "modules" => $this->modules]);
    }

    private function init_single_module(int $module_flag, array $args){
        $httpModuleClass = $this->moduleClasses[$module_flag];
        $httpModuleClass;
        $this->modules[$module_flag] = $httpModuleClass
            ->newInstance(...$args);
    }

    private function listen(){
        $host = 'localhost';
        $port = $this->port;
        $http_module = $this->modules[SarkozyModule::HTTP_MODULE];

        $server = stream_socket_server("tcp://$host:$port", $errno, $errstr);
        if (!$server) {
            die("Runtime error : server failed to start $errstr ($errno)\n");
        }
        
        while ($client = stream_socket_accept($server)) {
            $request = $http_module->get_request($client);
            var_dump($request);
            //$http_module->handle_request($request);

        }
        
        fclose($server);
    }
}



?>