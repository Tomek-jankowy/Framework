<?php

use App\Core\Settings\Service\FileManager;

// function hook_run()
// {
//     $fileManager = new FileManager;
//     $modulelist = $fileManager->loadFile('modulelist');
//     foreach( array_keys($modulelist) as $name){
//         $name .= "_run";
//         if( function_exists($name) ){
//             $name();
//         }
//     }
// }