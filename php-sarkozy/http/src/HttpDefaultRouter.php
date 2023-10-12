<?php

namespace PhpSarkozy\Http;

use PhpSarkozy\core\api\SarkontrollerRequest;

/**
 * A router with very limited features for tests while RouterModule is not activated
 */
class HttpDefaultRouter{
    
    public static function get_call(string $path): SarkontrollerRequest{
        
        $path_parts = preg_split("/\?/", preg_replace('/[\/\.]/', "", $path), 1);
        $cleanPath = $path_parts[0];
        $rawArgs = count($path_parts) > 1 ? $path_parts[1]: null;
        $args = array();
        if ($rawArgs != null){
            parse_str($rawArgs, $args);
        }
        echo $cleanPath;
        return new SarkontrollerRequest(0, $cleanPath, $args);

    }

}

?>