<?php

namespace core;

use attributes\SarkozyModule;

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
        $this->controllers = \core\utils\ControllerUtils::get_all_controllers();
        $this->moduleClasses = \core\utils\ModuleUtils::get_all_modules();
        $this->init_modules();
    }


    //TODO: check modules definition
    private function init_modules()
    {
        //HTTP Module
        $this->init_single_module(SarkozyModule::HTTP_MODULE, ["controllers" => $this->controllers]);
    }

    private function init_single_module(int $module_flag, array $args){
        $httpModuleClass = $this->moduleClasses[$module_flag];
        $httpModuleClass;
        $this->modules[$module_flag] = $httpModuleClass
            ->newInstance($args);
    }


}



?>