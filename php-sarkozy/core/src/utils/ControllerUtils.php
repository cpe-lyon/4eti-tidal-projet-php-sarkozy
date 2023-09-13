<?php

namespace PhpSarkozy\core\utils;

use PhpSarkozy\core\attributes\Sarkontroller;

/**
 * Utils for Sarkontroller gestion
 */
final class ControllerUtils
{
    
 
    public static function get_all_controllers(): array{
        $reflectionControllers = array();
        foreach( get_declared_classes() as $class){
            $reflectionClass = new \ReflectionClass($class);
            if ( count(
                $reflectionClass->getAttributes(Sarkontroller::class, \ReflectionAttribute::IS_INSTANCEOF)
            ) > 0){
                $reflectionControllers[] = $reflectionClass;
            }
        }

        return $reflectionControllers;
    }

}


?>