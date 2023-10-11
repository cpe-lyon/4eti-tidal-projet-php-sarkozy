<?php

namespace PhpSarkozy\LeTempsDesTemplates\Elements\;

// Element statique: du contenu HTML à renvoyer brut
class StaticElement implements TemplateElement {

    public static function process(array $variables): string {
        return implode($variables);
    }
}