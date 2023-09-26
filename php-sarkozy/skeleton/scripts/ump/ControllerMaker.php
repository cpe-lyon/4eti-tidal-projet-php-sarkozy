<?php

class ControllerMaker{

    const CONTENT_HEADER = "<?php\r\n\r\n"."use PhpSarkozy\core\attributes\Sarkontroller;\r\n\r\n";
    const CONTENT_FOOTER = "\r\n\r\n?>\r\n";

    static private function get_content($name, array $methods){

        $content = "";

        foreach ($methods as $methodName => $_value) {
            $content.= "  public function $methodName(){\r\n    return '';\r\n  }\r\n"; 
        }

        return ControllerMaker::CONTENT_HEADER."#[Sarkontroller]\r\nclass $name{\r\n$content\r\n}".ControllerMaker::CONTENT_FOOTER;
    }

    static private function check_and_get_file($name){
        if ($name == ''){
            throw new Exception("Controller name should not be empty");
        }
        if (preg_match('/[^A-Za-z]/', $name)){
            throw new Exception("Controller name should only contain ASCII letters");
        }
        $name = strtoupper($name[0]).substr($name, 1);
        return  __DIR__."/../../src/controllers/$name.php";

    }

    static private function parse_args(array $args){
        if(count($args) < 2){
            throw new Exception("Usage: $args[0] [options] <controller-name>");
        }

        $methodFlag = 0;

        $methods = array();
        $name = null;
        foreach(array_slice($args, 1) as $a){
            if( $a!='' && $a[0] == '-'){
                foreach(str_split(substr($a, 1)) as $c){
                    switch($c){
                        case 'm':
                            $methodFlag = 1;
                            break;
                        default:
                            throw new Exception("Unknown option flag '$c'");
                    }
                }
            }else{
                if($methodFlag){
                    $methods[$a] = array();
                    $methodFlag = 0;
                }else if ($name != null){
                    throw new Exception("Two controller names were given: 1 needed");
                }else{
                    $name = $a;
                }

            }
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