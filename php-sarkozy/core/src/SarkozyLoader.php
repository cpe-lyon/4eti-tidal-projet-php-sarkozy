<?php

namespace PhpSarkozy\core;


class SarkozyLoader{
    public function __construct(string $dir, $subdirs=false)
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir),
            \RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                require_once $file;
            }
        }
    }
} 

?>