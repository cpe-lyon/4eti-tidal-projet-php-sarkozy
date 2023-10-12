<?php

require_once "ControllerMaker.php";

ControllerMaker::init();

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
        $inp = readline(" > ");
        readline_add_history($inp);
        return $inp;
    }

    private function clean_arg($arg){
        if($arg[0] == '"' || $arg[0] == '\''){
            return substr($arg, 1, strlen($arg)-2);
        }
        return $arg;
    }

    private function make_controller($args){
        try {
            ControllerMaker::make_controller($args);
        } catch( Exception $e){
            $this->disperror($e->getMessage());
        }

    }


    private function help_cmd($cmd, $desc){
        if($this->shellcolored){
            echo ShellTool::CWHT.ShellTool::CBLU.$cmd.ShellTool::CNOR." $desc\r\n";
        }else{
            echo "$cmd $desc\r\n";
        }
    }

    private function help_opt($cmd, $desc){
        if($this->shellcolored){
            echo '  '.ShellTool::CWHT.ShellTool::CBLU.'*'.ShellTool::CNOR." $cmd $desc\r\n";
        }else{
            echo "  * $cmd $desc\r\n";
        }
    }

    private function help(){
        echo "\r\n";
        $this->help_cmd("mk-controller", "Make a controller");
        $this->help_opt("<controller-name>", "ASCII letters only, name of the class");
        $this->help_opt("-m <method-name>", "snake_case method name (optional, can be used several times in a single command)");
        $this->help_opt("-mr <method-name> <path>", "add path to method: in-path arguments-> '/resource/[arg]' , arguments -> '/resource?arg1&arg2' ");
        echo "\r\n";
        $this->help_cmd("exit", "Exit UMP");
        echo "\r\n";
    }

    public function exec(array $raw_args){
        if(count($raw_args) == 0){
            return;
        }
        $args = array_map(
            function($r){return $this->clean_arg($r);},
            $raw_args
        );
        switch ($args[0]) {
            case 'mk-controller':
                $this->make_controller($args);
                break;
            case 'help':
                $this->help();
                break;
            case 'exit':
                return 1;
            default:
                $this->disperror('Unknown command, type "help" to display available commands');
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
    preg_match_all('/(?:[^\s"\'][^\s]*|\'(?:\\\'|[^\']+)*\'|"(?:\\"|[^"]+)*")/', $prompt, $matches );
    $args = $matches[0];
    if ($sh->exec($args)){
        $continu = false;
    }

}


?>