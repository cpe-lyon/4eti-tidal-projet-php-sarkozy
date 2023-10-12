<?php

namespace PhpSarkozy\LeTempsDesTemplates\elements;

// Element conditionnel(if): prends un bloc de condition (de .if à .end), vérifie la condition (prédéfinie dans l'array $variables) puis retourne la string vérifiée
class ConditionalElement implements TemplateElement {

    private TemplateElement $element_yes;
    private ?TemplateElement $element_no= null;
    
    private string $condition = False;


    function parse_elements(array &$src){
        for ($idx=0; $src[$idx])
    }

    function __construct(string $condition, array &$array){
        $this->condition = $condition;
        $last_el = null;
        $this->element_yes = new ContentElement($array, $last_el);
        
        if ($last_el instanceof ElseElement){
            $this->element_no = new ContentElement($array, $last_el);
            if ($last_el == null || ! ($last_el instanceof EndElement)){
                throw new Exception(".else could not match an .end");
            }
        }else if ( !($last_el instanceof EndElement)){
            throw new Exception(".if could not match an .end");
        }

    }

    public function process(array $variables): string{

        $computed_cond = preg_replace('/\{([^}]+)\}/', '\$variables["$1"]', $this->condition);

        if(eval("return $computed_cond;")){
            return $this->element_yes->process($variables);
        }

        if ($element_no != null){
            return $this->element_no->process($variables);
        }

        return "";
    }

}