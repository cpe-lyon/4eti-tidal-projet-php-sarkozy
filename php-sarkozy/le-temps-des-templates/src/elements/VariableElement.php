<?php

namespace PhpSarkozy\LeTempsDesTemplates\Elements\;

// Element variable: un élément contenant un ou plusieurs variables prédéfinies dans l'array $variables. On renvoie le contenu avec le contenu des var dans le texte
class VariableElement implements TemplateElement {

    private array $array = array();

    function __construct(array $array){
        $this->array = $array;
    }

    public function process(array $variables):string {

        string $content = implode($this->array);

        foreach ($variables as $variable => $value) {
            $content = str_replace("{{ $variable }}", $value, $content);
        }

        return $content;
    }
}