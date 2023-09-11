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
        $this->controllers = \core\utils\ControllerUtils::get_all_controllers();
        $this->moduleClasses = \core\utils\ModuleUtils::get_all_modules();
        $this->init_modules();
    }


    //TODO: check modules definition
    private function init_modules()
    {
        $this->init_single_module(SarkozyModule::TEMPLATE_MODULE, ["path" => $this->viewsPath]);

        //HTTP Module should be last
        $this->init_single_module(SarkozyModule::HTTP_MODULE, ["controllers" => $this->controllers]);
    }

    private function init_single_module(int $module_flag, array $args){
        $httpModuleClass = $this->moduleClasses[$module_flag];
        $httpModuleClass;
        $this->modules[$module_flag] = $httpModuleClass
            ->newInstance(...$args);
    }


}



?>