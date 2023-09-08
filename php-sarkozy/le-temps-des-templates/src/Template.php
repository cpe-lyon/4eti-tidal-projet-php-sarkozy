<?php

class Template{

    private $variables = array();
    private $file;

    function __construct($file){
        $this->file = $file;
    }

    public function assign($variable, $value) {
        $this->variables[$variable] = $value;
    }
    
    public function render() {
        if (file_exists($file)) {
            $content = file_get_contents($file);
            
            foreach ($this->variables as $variable => $value) {
                $content = str_replace("{{ $variable }}", $value, $content);
            }
            
            return $content;
        } else {
            throw new Exception("Le fichier de modèle '$file' n'existe pas.");
        }
    }

}

?>