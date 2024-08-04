<?php

spl_autoload_register(function ($classname) {
    $source =  'config/class/' . $classname . '.php';

    if (file_exists($source)) {

        require_once realpath($source);
    } else {
        echo "Class $classname not found at: " . $source . "<br>";
    }
});
