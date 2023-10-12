<?php

namespace PhpSarkozy\LeTempsDesTemplates\elements;

interface TemplateElement {
    function process(array $variables): string;
}