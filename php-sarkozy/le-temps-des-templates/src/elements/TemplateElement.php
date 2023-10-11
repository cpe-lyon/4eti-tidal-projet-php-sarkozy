<?php

namespace PhpSarkozy\LeTempsDesTemplates;

interface TemplateElement {
    function process(array $variables): string;
}