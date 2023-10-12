<?php

namespace PhpSarkozy\Http\models;

use PhpSarkozy\Http\attributes\HttpAttributeInterface;
use ReflectionMethod;

class HttpControllerRecord{

    public string $class_name;

    /**
     * @var HttpAttributeInterface[]
     */
    public array $attributes;

    
    /**
     * @var HttpControllerMethodRecord[]
     */
    public array $methods;


    /**
     * @param \ReflectionAttribute<HttpAttributeInterface> $attr
     */
    private function instanciate($attr){
        return $attr->newInstance();
    }


    public function __construct(\ReflectionClass $class){
        $this->class_name = $class->getName();
        $attr_classes = $class->getAttributes(HttpAttributeInterface::class, \ReflectionAttribute::IS_INSTANCEOF);
        $this->attributes = array_map( fn($a) => $this->instanciate($a) , $attr_classes);
        $raw_methods = $class->getMethods( ReflectionMethod::IS_PUBLIC );
        $this->methods = array_combine(
            array_map(fn($m)=>$m->getName(), $raw_methods),
            array_map(fn($m)=> new HttpControllerMethodRecord($m), $raw_methods),
        );
    }

}

?>