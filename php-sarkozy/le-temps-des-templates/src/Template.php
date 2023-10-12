<?php

namespace PhpSarkozy\LeTempsDesTemplates\;

use PhpSarkozy\LeTempsDesTemplates\TemplateInstructionEnum;
use PhpSarkozy\LeTempsDesTemplates\elements\ContentElement;


class Template{

    private $variables = array();
    private $file;
    const REGEX = '/\{\{((?:[^}]+|\}[^}])*)\}\}/';
    const INSTRUCTION_REGEX = '/\s*\.([^\s]+)\s*(.*)\s*$/';

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
            
            // Les index impaires sont les str à remplacer, fonction process qui rend un tableau processed puis fonction join qui renvoi l'html
            $splitted_content = preg_split(Template::REGEX, $content, -1, PREG_SPLIT_DELIM_CAPTURE);

            $content_element = new ContentElement($splitted_content);
            $computed_content = $content_element->process($this->variables);
            
            return $computed_content;
        } else {
            throw new \Exception("Le fichier de modèle '$this->file' n'existe pas.");
        }
    }


}

?>