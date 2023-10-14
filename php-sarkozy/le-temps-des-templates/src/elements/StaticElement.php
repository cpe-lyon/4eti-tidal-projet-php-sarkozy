<?php

namespace PhpSarkozy\LeTempsDesTemplates\elements;

// Element statique: du contenu HTML à renvoyer brut
class StaticElement implements TemplateElement {

    private string $str;

    public function __construct(string $str){
        $this->str = $str;
    }

    public function process(array $variables): string {
        return $this->str;
    }
}