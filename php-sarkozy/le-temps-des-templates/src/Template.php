<?php

namespace PhpSarkozy\LeTempsDesTemplates;

class Template{

    private $variables = array();
    private $file;
    const REGEX = '/\{\{((?:[^}]+|\}[^}])*)\}\}/';

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
            // Les index impaires sont les str à remplacer, fonction process qui rend un tableau processed puis fonction join qui renvoi l'html
            $splitted_content = preg_split(Template::REGEX, $content, -1, PREG_SPLIT_DELIM_CAPTURE);

            $computed_content = process($splitted_content);
            /*
            foreach ($this->variables as $variable => $value) {
                $content = str_replace("{{ $variable }}", $value, $content);
            }*/
            
            return $content;
        } else {
            throw new \Exception("Le fichier de modèle '$this->file' n'existe pas.");
        }
    }


    public function process($splitted_array): array{
        return new array();
    }

    public function join(){
        
    }

}

?>