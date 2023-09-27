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

    private static function check_and_add(\ReflectionClass $moduleClass, string $funcName, array $argslist): bool{
        if ($moduleClass->hasMethod($funcName)) return true;
        $className = $moduleClass->getShortName();
        echo "Function $funcName is missing in $className\n";
        $rawArgList = "";
        foreach ($argslist as $arg) {
            $rawArgList .= "$$arg ,";
        }
        if (strlen($rawArgList)) $rawArgList = substr($rawArgList, 0, -1);
        $newMethod = "    public function $funcName($rawArgList) {}";
        $filename = $moduleClass->getFileName();
        $sourceCode = file_get_contents($filename);
        $sourceCode = preg_replace("/(class\s+${className}(?:[^{]+|\s+)*\{)/", "$0\n".$newMethod, $sourceCode);
        file_put_contents($filename, $sourceCode);
        echo " -> Added it !\n";
        return false;
    }

    private static function check_protocol_module($moduleClass){
        if($moduleClass == null){
            throw new ModuleLoadingException("No Protocol module detected");
        }
        $complete =  ModuleUtils::check_and_add($moduleClass, "get_request", ["client"]);
        $complete &= ModuleUtils::check_and_add($moduleClass, "get_raw_response", ["request"]);
        $complete &= ModuleUtils::check_and_add($moduleClass, "get_call", ["request"]);
        $complete &= ModuleUtils::check_and_add($moduleClass, "handle_response", ["request", "controller_response"]);
        if (!$complete){
            throw new ModuleLoadingException("Incorrect protocol module");
        }
    }

    public static function check_modules($modulesClass){        
        ModuleUtils::check_protocol_module($modulesClass[SarkozyModule::PROTOCOL_MODULE]);
    }

}

/**
 * Exception relative to ControllerUtils
 */
class ModuleLoadingException extends Exception{
}

?>