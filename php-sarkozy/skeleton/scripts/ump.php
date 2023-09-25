<?php

class ShellTool{

    private $shellcolored;

    function __construct()
    {
        $this->shellcolored = function_exists('posix_isatty') && posix_isatty(STDOUT);
    }
    const CRED="\033[41m";

    const CBLU = "\033[44m";
    const CNOR = "\033[0m";
    const CWHT = "\033[1;37m";

    public function welcome_print(string $msg, int $bluelen){
        if($this->shellcolored){
            echo ShellTool::CWHT.ShellTool::CBLU.substr($msg, 0, $bluelen).ShellTool::CRED.substr($msg, $bluelen).ShellTool::CNOR."\n";
        }else{
            echo "$msg\n";
        }
    }

    public function disperror(string $msg){
        if ($this->shellcolored){
            echo ShellTool::CRED.$msg.ShellTool::CNOR."\n";
        }else{
            echo "$msg\n";
        } 
    }

    public function ask():string{
        return readline(" > ");
    }

    private function clean_arg($arg){
        if($arg[0] == '"' || $arg[0] == '\''){
            return substr($arg, 1, strlen($arg)-2);
        }
        return $arg;
    }

    private function make_controller($args){
        if(count($args) != 2){
            $this->disperror("Usage: $args[0] <controller-name>");
            return;
        }
        $name = $args[1];
        if ($name == ''){
            $this->disperror("Controller name should not be empty");
            return;
        }
        if (preg_match('/[^A-Za-z]/', $name)){
            $this->disperror("Controller name should only contain ASCII letters");
            return;
        }
        $name = strtoupper($name[0]).substr($name, 1);
        $file = __DIR__."/../src/controllers/$name.php";
        if(file_exists($file)){
            $this->disperror("Can't create controller: $name.php already exists");
            return;
        }
        
        $content = "<?php\r\n\r\n".
            "use PhpSarkozy\core\attributes\Sarkontroller;\r\n\r\n".
            "#[Sarkontroller]\r\n".
            "class $name{\r\n}\r\n\r\n".
            "?>\r\n";
        if( ($fd = fopen($file, 'w')) == false){
            $this->disperror("Can't create controller: Could not create $name.php");
            return;
        }
        if (fwrite($fd, $content) === false) {
            $this->disperror("Can't create controller: Could not write in $name.php");
            return;
        }
        fclose($fd);

        echo "$name created !\n";

    }

    public function exec(array $rawArgs){
        if(count($rawArgs) == 0){
            return;
        }
        $args = array_map(
            function($r){return $this->clean_arg($r);},
            $rawArgs
        );
        switch ($args[0]) {
            case 'mk-controller':
                $this->make_controller($args);
                break;
            case 'exit':
                return 1;
            default:
                $this->disperror("Unknown command");
                break;
        }
        return 0;
    }

}

$sh = new ShellTool();
$sh->welcome_print("-----------------------", 11);
$sh->welcome_print(" Utils Making prompter ", 11);
$sh->welcome_print("-----------------------", 11);

$continu = true;

while($continu){
    $prompt = $sh->ask();
    preg_match_all('/(?:[^\s"\'][^\s]+|\'(?:\\\'|[^\']+)*\'|"(?:\\"|[^"]+)*")/', $prompt, $matches );
    $args = $matches[0];
    if ($sh->exec($args)){
        $continu = false;
    }

}


?>