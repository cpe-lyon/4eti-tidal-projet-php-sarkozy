<?php

namespace PhpSarkozy\LeTempsDesTemplates\Elements\;

// Element conditionnel(if): prends un bloc de condition (de .if à .end), vérifie la condition (prédéfinie dans l'array $variables) puis retourne la string vérifiée
class ConditionalElement implements TemplateElement {

    private array $array = array();
    private bool $condition = False;

    function __construct(array $array, bool $condition){
        $this->array = $array;
        $this->condition = $condition;
    }

    public function process(array $variables): string{

        $content = "";

        // Check de la condition
        if(){
            // Gestion du .if .else
            if(array_search(".else", $this->array) === False){
                foreach ($this->array as $content) {
                    
                }
            }else{

            }
        }



        return "";
    }
}