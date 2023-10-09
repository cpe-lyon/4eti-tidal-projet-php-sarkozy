<?php

namespace PhpSarkozy\Http\models;

use PhpSarkozy\Http\attributes\HttpAttributeInterface;
use ReflectionMethod;

class HttpControllerRecord{

    public string $className;

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
        $this->className = $class->getName();
        $attrClasses = $class->getAttributes(HttpAttributeInterface::class, \ReflectionAttribute::IS_INSTANCEOF);
        $this->attributes = array_map( fn($a) => $this->instanciate($a) , $attrClasses);
        $rawMethods = $class->getMethods( ReflectionMethod::IS_PUBLIC );
        $this->methods = array_combine(
            array_map(fn($m)=>$m->getName(), $rawMethods),
            array_map(fn($m)=> new HttpControllerMethodRecord($m), $rawMethods),
        );
    }

}

?>