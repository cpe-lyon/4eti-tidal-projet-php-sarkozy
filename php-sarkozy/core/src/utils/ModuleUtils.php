<?php

namespace PhpSarkozy\core\utils;

use Exception;
use PhpSarkozy\core\attributes\SarkozyModule;

/**
 * Utils for modules gestion
 */
final class ModuleUtils
{
    
    public static function get_all_modules(): array{
        $reflection_modules = array();
        foreach( get_declared_classes() as $class){
            $reflection_class = new \ReflectionClass($class);
            $reflection_attributes = $reflection_class->getAttributes(SarkozyModule::class, \ReflectionAttribute::IS_INSTANCEOF);
            if (count($reflection_attributes) > 0){
                $module_flag = $reflection_attributes[0]->getArguments()[0];
                $reflection_modules[$module_flag] = $reflection_class;
            }
        }

        return $reflection_modules;
    }

    private static function check_and_add(\ReflectionClass $module_class, string $func_name, array $argslist): bool{
        if ($module_class->hasMethod($func_name)) return true;
        $class_name = $module_class->getShortName();
        echo "Function $func_name is missing in $class_name\n";
        $raw_arg_list = "";
        foreach ($argslist as $arg) {
            $raw_arg_list .= "$$arg ,";
        }
        if (strlen($raw_arg_list)) $raw_arg_list = substr($raw_arg_list, 0, -1);
        $new_method = "    public function $func_name($raw_arg_list) {}";
        $filename = $module_class->getFileName();
        $source_code = file_get_contents($filename);
        $source_code = preg_replace("/(class\s+${class_name}(?:[^{]+|\s+)*\{)/", "$0\n".$new_method, $source_code);
        file_put_contents($filename, $source_code);
        echo " -> Added it !\n";
        return false;
    }

    private static function check_protocol_module($module_class){
        if($module_class == null){
            throw new ModuleLoadingException("No Protocol module detected");
        }
        $complete =  ModuleUtils::check_and_add($module_class, "get_request", ["client"]);
        $complete &= ModuleUtils::check_and_add($module_class, "get_raw_response", ["request"]);
        $complete &= ModuleUtils::check_and_add($module_class, "get_call", ["request"]);
        $complete &= ModuleUtils::check_and_add($module_class, "handle_response", ["request", "controller_response"]);
        if (!$complete){
            throw new ModuleLoadingException("Incorrect protocol module");
        }
    }

    public static function check_modules($modules_class){        
        ModuleUtils::check_protocol_module($modules_class[SarkozyModule::PROTOCOL_MODULE]);
    }

}

/**
 * Exception relative to ControllerUtils
 */
class ModuleLoadingException extends Exception{
}

?>