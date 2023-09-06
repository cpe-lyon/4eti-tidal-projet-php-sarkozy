<?php

namespace core;


class SarkozyServer
{


    /**
     * Runs the server
     *
     * @param int $port The server port
     * @return void
     **/
    public static function run_server(int $port = 2007)
    {
        var_dump(SarkozyServer::get_controller_classes());
    }


    private static function get_controller_classes() : array{
        $children = array();
        foreach( get_declared_classes() as $class ){
            if( is_subclass_of( $class, Sarkontroller::class ) )
                $children[] = $class;
        }
        return $children;
    }

}



?>