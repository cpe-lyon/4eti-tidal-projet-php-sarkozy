<?php

namespace PhpSarkozy\core\api;

interface SarkoView{
    function get_view_args(): array;
    function get_view_reference(): string;
}

?>