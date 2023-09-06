<?php

namespace core\utils;

use attributes\SarkozyModule;

/**
 * Utils for modules gestion
 */
final class ModuleUtils
{
    

 
    public static function get_all_modules(): array{
        $reflectionModules = array();
        foreach( get_declared_classes() as $class){
            $reflectionClass = new \ReflectionClass($class);
            $reflectionAttributes = $reflectionClass->getAttributes(SarkozyModule::class, \ReflectionAttribute::IS_INSTANCEOF);
            if (count($reflectionAttributes) > 0){
                $module_flag = $reflectionAttributes[0]->getArguments()[0];
                $reflectionModules[$module_flag] = $reflectionClass;
            }
        }

        return $reflectionModules;
    }

}


?>