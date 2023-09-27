<?php

namespace PhpSarkozy\LeTempsDesTemplates;

class Template{

    private $variables = array();
    private $file;

    function __construct($file){
        $this->file = $file;
    }

    public function assign($variable, $value) {
        $this->variables[$variable] = $value;
    }

    public function array_assign($args){
        $this->variables = $args;
    }
    
    public function render() {
        if (file_exists($this->file)) {
            $content = file_get_contents($this->file);
            
            //TODO: use preg_match (regex) instead of str_replace
            foreach ($this->variables as $variable => $value) {
                $content = str_replace("{{ $variable }}", $value, $content);
            }
            
            return $content;
        } else {
            throw new \Exception("Le fichier de modèle '$this->file' n'existe pas.");
        }
    }

}

?>