<?php

foreach (glob(__DIR__.'/*.php') as $filename) {
    if ('All' === basename($filename, '.php')) {
        continue;
    }
    include_once $filename;
}
