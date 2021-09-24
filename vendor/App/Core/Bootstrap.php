<?php
$start = microtime(true);
include __DIR__."/../../autoload.php";
include "Run.php";


run();
hook_run();
hook_install();

loadRouting();


$end = microtime(true);
$time = $end-$start;
dump($time);