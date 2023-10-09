<?php

namespace PhpSarkozy\Http\models;

use PhpSarkozy\Http\attributes\HttpAttributeInterface;

class HttpControllerMethodRecord{

    public string $methodName;

    /**
     * @var HttpAttributeInterface[]
     */
    public array $attributes;


    /**
     * @var array<string,HttpAttributeInterface>
     */
    public array $params;


    /**
     * @param \ReflectionAttribute<HttpAttributeInterface> $attr
     */
    private function instanciate($attr){
        return $attr->newInstance();
    }

    private function parseParams(\ReflectionParameter $param){
        $name =  $param->getName().($param->isOptional()?'?':'');
        $attrClasses = $param->getAttributes(HttpAttributeInterface::class, \ReflectionAttribute::IS_INSTANCEOF);
        $value = array_map( fn($a) => $this->instanciate($a) , $attrClasses);
        return [ $name => $value ];
    }

    public function __construct(\ReflectionMethod $method){
        $this->methodName = $method->getName();
        $attrClasses = $method->getAttributes(HttpAttributeInterface::class, \ReflectionAttribute::IS_INSTANCEOF);
        $this->attributes = array_map( fn($a) => $this->instanciate($a) , $attrClasses);
        $this->params = array_map( fn($p) => $this->parseParams($p), $method->getParameters());
    }

}

?>