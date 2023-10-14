<?php

namespace PhpSarkozy\LeTempsDesTemplates;

enum TemplateInstructionEnum: string{
    case IF = 'if';
    case ELSE = 'else';
    case END = 'end';
}