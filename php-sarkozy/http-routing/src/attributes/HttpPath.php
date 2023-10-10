<?php

namespace PhpSarkozy\routing\attributes;

use PhpSarkozy\Http\attributes\HttpAttributeInterface;
use PhpSarkozy\Http\utils\HttpMethodsEnum;
use PhpSarkozy\Http\utils\HttpMethodUtils;

#[\Attribute(\Attribute::TARGET_METHOD)]
class HttpPath implements HttpAttributeInterface{

    public readonly String $pathreg;

    public readonly int $priority;

    public readonly HttpMethodsEnum $method;

    function init_path(string $path){
        $trailing_slash = str_ends_with($path, '/');
        if ($trailing_slash){
            $path = substr($path, 0, strlen($path)-1);
        }
        
        $splitted =explode('/', $path, -1);
        $slashes = count($splitted);

        $match_all_idx = $trailing_slash ? -1 : $slashes-1;

        $this->pathreg = '/^';

        //This variable will be doubled $slashes time
        $prio = 1;

        foreach ($splitted as $key=>$tag) {
            $prio *= 2;
            $match = array();
            $is_argument = preg_match('/^\[(.*)\]$/', $tag) != false;
            if ( !$is_argument){
                //Is $slashes equal, "not argument" is prioritary 
                $prio += 1;
                $this->pathreg .= '\\/'.urlencode($tag);
            }else{
                //sanitize group name
                $group = preg_replace(
                    '/[^A-Za-z0-9_]/',
                    "",
                    $match[1]
                );

                $reg='[^\\/]';
                if ($key == $match_all_idx ){
                    $reg='.*';
                }

                $this->pathreg .= "\\/(?<{$group}>$reg)";
            }
        }
        $this->pathreg .= '$/';
        $this->priority = $prio;
    }

    function __construct(String $path, String $method){
        $this->method = HttpMethodUtils::parse_method($method);
        $this->init_path($path);
    }

    
}