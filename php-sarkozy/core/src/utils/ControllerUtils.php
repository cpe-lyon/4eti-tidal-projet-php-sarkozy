<?php

namespace PhpSarkozy\core\utils;

use PhpSarkozy\core\attributes\Sarkontroller;

/**
 * Utils for Sarkontroller gestion
 */
final class ControllerUtils
{
    
 
    public static function get_all_controllers(): array{
        $reflection_controllers = array();
        foreach( get_declared_classes() as $class){
            $reflection_class = new \ReflectionClass($class);
            if ( count(
                $reflection_class->getAttributes(Sarkontroller::class, \ReflectionAttribute::IS_INSTANCEOF)
            ) > 0){
                $reflection_controllers[] = $reflection_class;
            }
        }

        return $reflection_controllers;
    }

}


?>