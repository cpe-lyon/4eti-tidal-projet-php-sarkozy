<?php

namespace PhpSarkozy\LeTempsDesTemplates\elements;

// Element variable: un élément contenant un ou plusieurs variables prédéfinies dans l'array $variables. On renvoie le contenu avec le contenu des var dans le texte
class VariableElement implements TemplateElement {

    private string $str;

    function __construct(string $str){
        $this->str = $str;
    }

    public function process(array $variables):string {

        if (!isset($variables[$this->str])){
            throw new Exception("Variable not found: $this->str", 500);
        }

        return strval($variables[$this->str]);
    }
}