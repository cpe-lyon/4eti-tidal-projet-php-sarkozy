<?php

namespace PhpSarkozy\LeTempsDesTemplates\;

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
            
            // Les index impaires sont les str à remplacer, fonction process qui rend un tableau processed puis fonction join qui renvoi l'html
            $splitted_content = preg_split(Template::REGEX, $content, -1, PREG_SPLIT_DELIM_CAPTURE);

            $computed_content = $this->process($splitted_content);
            /*
            foreach ($this->variables as $variable => $value) {
                $content = str_replace("{{ $variable }}", $value, $content);
            }*/
            
            return $computed_content;
        } else {
            throw new \Exception("Le fichier de modèle '$this->file' n'existe pas.");
        }
    }


    public function process($splitted_array): string{

        // Créé l'arbre du template
        for($i = 1; $i < count($splitted_array); $i+=2){
            if(preg_replace('/\s+/', '', $splitted_array[$i]) == "if") {
                echo "trouvé";
            }
        }

        return implode($splitted_array);
    }
}

?>