<?php

namespace PhpSarkozy\LeTempsDesTemplates\elements;

use PhpSarkozy\LeTempsDesTemplates\elements\StaticElement;
use PhpSarkozy\LeTempsDesTemplates\elements\VariableElement;
use PhpSarkozy\LeTempsDesTemplates\elements\ConditionalElement;

class ContentElement implements TemplateElement {
    

    private array $template_elements;

    private static function is_end_element(TemplateElement $el){
        return ($el instanceof ElseElement);
    }

    private function create_instruction_element(TemplateInstructionEnum $instruction, string $arg, array &$src){
        switch ($instruction) {
            case TemplateInstructionEnum::IF :
                return new Conditionalelement($arg, $src);
            case TemplateInstructionEnum::ELSE:
                return new ElseElement();
        }
    }

    private function get_instruction(TemplateInstructionEnum $instruction, string $arg, int $idx, array &$src, bool &$stop){
        $element = $this->create_instruction_element($instruction, $arg, $src);

        if ( ContentElement::is_end_element($element) ){
            $stop = true;
            $src = array_slice($src, $idx+1);
        }

        return $element;
    }

    /**
     * @param string[] $src
     */
    private function create_content_element(array &$src){

        $template_arr = array();
        $stop = false;
        for ($idx=0; $stop || $idx<count($src); $idx++) {
            $el = $src[$idx];
            if ($idx%2 == 0){
                $template_arr[] = new StaticElement($el);
            }else{
                $instruction_match = array();
                if (!preg_match(Template::INSTRUCTION_REGEX, $el, $instruction_match)){
                    //If we are not using instruction, just insert
                    $template_arr[] = new VariableElement($el);

                }else{
                    //Else, we use instruction_match:
                    //$instruction_match[1] contains the instruction (example: 'if')
                    //$instruction_match[2] contains the other parameters (example: '{title} == "My Page"')
                    
                    $raw_instruction=  $instruction_match[1];
                    try {
                        $instruction = TemplateInstructionEnum::from($raw_instruction);
                    } catch (\ValueError $th) {
                        throw new \Exception("Unknown instruction: $raw_instruction");
                    }
                    
                    $template_arr[] = $this->create_instruction_element(
                        $instruction, $instruction_match[2], $idx,
                        $src, $stop
                    );
                    
                }
            }
        }
        $this->template_elements = $template_arr;
    }


    function __construct(array &$src, &$end_element=null){
        $this->create_content_element($src);
        $element_l = count($this->template_elements);
        $last_element = $element_l == 0 ? null : $this->template_elements[$element_l-1];
        if(ContentElement::is_end_element($last_element)){
            $end_element = $last_element;
        }
    }


    public function process(array $variables): string{
        return implode(
            array_map(
                fn($el): $el->process($variables),
                $this->template_elements
            )
        );
    }

}