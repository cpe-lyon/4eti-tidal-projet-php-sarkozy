<?php

namespace PhpSarkozy\core\utils;

class LogUtils{
    
    public static function echo_welcome($url){

        $left = false;
        for ($i=strlen($url); $i<34; $i++){
            if ($left){
                $url = " $url";
            }else{
                $url = "$url ";
            }
            $left = !$left;
        }

        echo (
            "┏━━ Your PHP Sarkozy Server started! ━━┓\r\n".
            "MMMMMMMMWXko:,.         ..,:okXWMMMMMMMM\r\n".
            "MMMMMMXx:.             cOl.   .:xXMMMMMM\r\n".
            "MMMMKo'               .kMNl      'oKMMMM\r\n".
            "MMNx'                 .xMM0,      'xNMMM\r\n".
            "MXc     .;coddddolc,.  oWMNl    'dKKdoXM\r\n".
            "X:    .dXWWXK0KXNWWXO: :XMMx. .oXNk,  cX\r\n".
            "l     ;XMWx'....',,,'. .OMM0;:0W0c.    l\r\n".
            ".     .kMWo             dWMNXNXo.      .\r\n".
            "       'kW0,            :XMMNx'         \r\n".
            "        .xN0;           ,KMWk.          \r\n".
            "         .xWK:          :XMMNx'         \r\n".
            "          .kWXc         dWMMMMK:        \r\n".
            ";          cNM0,       .OMMOo0WNd.     ;\r\n".
            "k.       .;OWMNl       ;XMNl .oXWk'   .k\r\n".
            "Wk.  ,ldkKXNXKx'       oWMX;   ;ONO. .kW\r\n".
            "MWO,...'',''..        .kMMk.    .lkc;OWM\r\n".
            "MMMXd.                '0MNl       ;kXMMM\r\n".
            "MMMMWKd,              ,KMO'     ,dXWMMMM\r\n".
            "MMMMMMMNOo;.          .dO;  .;oONMMMMMMM\r\n".
            "MMMMMMMMMMN0dc'.       .',cx0WMMMMMMMMMM\r\n".
            "┗━ $url ━┛\r\n"
        );
    }

}

?>