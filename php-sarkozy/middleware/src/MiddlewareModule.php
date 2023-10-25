<?php
namespace PhpSarkozy\Middleware;

use PhpSarkozy\core\api\MiddlewareData;
use PhpSarkozy\core\api\Response;
use PhpSarkozy\core\api\Request;
use PhpSarkozy\Middleware\utils\PriorityUtils;
use PhpSarkozy\core\attributes\SarkozyModule;
use PhpSarkozy\Middleware\utils\MiddlewareModuleData;

#[SarkozyModule(SarkozyModule::MIDDLEWARE_MODULE)]
class MiddlewareModule {
    final const NAME = "MIDDLEWARE-MODULE";

    /**
     * @var \ReflectionClass[]
     */
    private array $middlewares_reflections;

    public function __construct($middlewares){
        $this->middlewares_reflections = PriorityUtils::get_all_middlewares_priorized($middlewares); 
        $this->init_middlewares_instances();
    }

    public function intercept_request(Request &$request): MiddlewareData{
        $data = new MiddlewareModuleData($this->init_middlewares_instances());
        foreach($data->get_instances() as $m){
            $request = $m->intercept_request($request);
        }
        return $data;
    }

    public function intercept_response(Response &$response, ?MiddlewareData $data){
        if ($data == null || !($data instanceof MiddlewareModuleData) ){
            $data = new MiddlewareModuleData($this->init_middlewares_instances());
        }
        foreach($data->get_instances() as $m){
            $response = $m->intercept_response($response);
        }
    }
    
    private function init_middlewares_instances(): array{
        $middleware_instances = array();
        foreach($this->middlewares_reflections as $m){
            $middleware_instance = $m["reflection_class"]->newInstance();
            array_push($middleware_instances, $middleware_instance);
        }
        return $middleware_instances;
    }
}

?>