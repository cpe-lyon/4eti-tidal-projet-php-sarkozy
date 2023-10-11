<?php

class ControllerMaker{

    const CONTENT_HEADER = "<?php\r\n\r\n"."use PhpSarkozy\core\attributes\Sarkontroller;\r\n\r\n";
    const CONTENT_FOOTER = "\r\n\r\n?>\r\n";


    private static function get_controller_dir(){
        return __DIR__."/../../src/controllers";
    }

    public static function init(){
        if (!is_dir(ControllerMaker::get_controller_dir())){
            mkdir(ControllerMaker::get_controller_dir());
        }
    }

    static private function get_content($name, array $methods){

        $has_path = false;
        $has_inpath = false;
        $content = "";

        $args = "";

        foreach ($methods as $methodName => $metamethod) {
            if (isset($metamethod["route"])){
                $path = str_replace('"', '\\"', $metamethod["route"]);
                $psplit = explode('?', $path, 2);
                $arglist = array();
                if (count($psplit) > 1){
                    $explicitargs = explode('&', $psplit[1]);
                    $arglist = $explicitargs == false ? array() : $explicitargs;
                }
                $route = $psplit[0];


                foreach($arglist as $a){
                    $args.= ($args==""?"":",")." \$$a ";
                }

                //in path
                $matches = array();
                preg_match_all('/\\[([^\\]]+)\\]/', $route, $matches);
                $inpatharglist= $matches[1];
                if (count($inpatharglist) > 0){
                    $has_inpath = true;
                }

                foreach($inpatharglist as $a){
                    $varname = $a;
                    //Prevent variable conflict
                    while( in_array($varname, $arglist)) {
                        $varname = "_$varname";
                    }

                    $args.= ($args==""?"":",")." #[HttpInPath(\"$a\")] \$$varname ";
                }

                $content .= "  #[HttpPath(\"$route\")]\r\n";
                $has_path = true;
            }

            $content.= "  public function $methodName($args){\r\n    return '';\r\n  }\r\n"; 
        }

        $additionalImports="";
        if($has_path){
            $additionalImports .= "use PhpSarkozy\\HttpRouting\\attributes\\HttpPath;\r\n";
        }
        if($has_inpath){
            $additionalImports .= "use PhpSarkozy\\HttpRouting\\attributes\\HttpInPath;\r\n";
        }


        return ControllerMaker::CONTENT_HEADER.$additionalImports."#[Sarkontroller]\r\nclass $name{\r\n$content\r\n}".ControllerMaker::CONTENT_FOOTER;
    }

    static private function check_and_get_file($name){
        if ($name == ''){
            throw new Exception("Controller name should not be empty");
        }
        if (preg_match('/[^A-Za-z]/', $name)){
            throw new Exception("Controller name should only contain ASCII letters");
        }
        $name = strtoupper($name[0]).substr($name, 1);
        return  ControllerMaker::get_controller_dir()."/$name.php";

    }

    static private function parse_args(array $args){
        if(count($args) < 2){
            throw new Exception("Usage: $args[0] [options] <controller-name>");
        }

        $methodFlag = 0;
        $route_flag= 0;

        $methods = array();
        $name = null;

        $last_method = null;
        foreach(array_slice($args, 1) as $a){
            if( $a!='' && $a[0] == '-'){
                foreach(str_split(substr($a, 1)) as $c){
                    switch($c){
                        case 'm':
                            $methodFlag = 1;
                            break;
                        case 'r':
                            $route_flag=1;
                            break;    
                        default:
                            throw new Exception("Unknown option flag '$c'");
                    }
                }
            }else{
                if($methodFlag){
                    $methods[$a] = array();
                    $methodFlag = 0;
                    $last_method = $a;
                }else if($route_flag && $last_method == null){
                    throw new Exception("You should use 'r' flag with 'm'");
                } else if($route_flag){
                    $methods[$last_method]["route"] = $a;
                    $last_method = null;
                    $route_flag = false;
                }else if ($name != null){
                    throw new Exception("Two controller names were given: 1 needed");
                }else{
                    $name = $a;
                }

            }
        }

        if ($methodFlag || $route_flag){
            throw new Exception("Incomplete flag");
        }


        return array(
            "name" => $name,
            "methods" => $methods
        );
    }

    static function check_methods(array $methods){
        foreach($methods as $m=>$_){
            if (preg_match('/[^A-Za-z_0-9]/', $m)){
                throw new Exception("Controller methods should only contain ASCII letters, underscore, or numbers");
            }
            if (str_starts_with($m, '__')){
                throw new Exception("You should not implement method starting with \"__\"");
            }
        }
    }

    static function make_controller($args){

        $parsedArgs = ControllerMaker::parse_args($args);

        $name = $parsedArgs['name'];
        $file=ControllerMaker::check_and_get_file($name);
        ControllerMaker::check_methods($parsedArgs['methods']);

        if(file_exists($file)){
            throw new Exception("Can't create controller: $name.php already exists");
            return;
        }

        $content = ControllerMaker::get_content($name, $parsedArgs['methods']);
        
        if( ($fd = fopen($file, 'w')) == false){
            throw new Exception("Can't create controller: Could not create $name.php");
        }
        if (fwrite($fd, $content) === false) {
            throw new Exception("Can't create controller: Could not write in $name.php");
        }
        fclose($fd);

        echo "$name created !\n";

    }

}

?>