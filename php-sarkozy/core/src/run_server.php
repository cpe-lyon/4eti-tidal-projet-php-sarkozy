<?php

namespace core;

/**
 * Runs the server
 *
 * @param int $port The server port
 * @return void
 **/
function run_server(int $port = 2007)
{
    get_controller_classes();
}


function get_controller_classes() : array{
    $children = array();
    foreach( get_declared_classes() as $class ){
        if( is_subclass_of( $class, 'Sarkontroller' ) )
            $children[] = $class;
    }
    return $children;
}

?>