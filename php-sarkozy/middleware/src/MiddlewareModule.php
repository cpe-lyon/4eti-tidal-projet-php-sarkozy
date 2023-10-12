<?php
namespace PhpSarkozy\Middleware;

use PhpSarkozy\core\api\Response;
use PhpSarkozy\core\api\Request;
use PhpSarkozy\Middleware\utils\PriorityUtils;
use PhpSarkozy\core\attributes\SarkozyModule;

#[SarkozyModule(SarkozyModule::MIDDLEWARE_MODULE)]
class MiddlewareModule {
    final const NAME = "MIDDLEWARE-MODULE";

    private array $middleware_instances;

    /**
     * @var \ReflectionClass[]
     */
    private array $middlewares_reflections;

    public function __construct($middlewares){
        $this->middlewares_reflections = PriorityUtils::get_all_middlewares_priorized($middlewares); 
        $this->init_middlewares_instances();
    }

    public function intercept_request(Request $request){
        foreach($this->middleware_instances as $m){
            $m->intercept_request($request);
        }
    }

    public function intercept_response(Response $response){
        foreach($this->middleware_instances as $m){
            $m->intercept_response($response);
        }
    }
    
    private function init_middlewares_instances(){
        $this->middleware_instances = array();
        foreach($this->middlewares_reflections as $m){
            $middleware_instance = $m["reflection_class"]->newInstance();
            array_push($this->middleware_instances, $middleware_instance);
        }
    }
}

?>