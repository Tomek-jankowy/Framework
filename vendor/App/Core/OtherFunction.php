<?php

use App\Core\Settings\Service\FileManager;

define('ERROR', 'error');
define('INFO', 'error');
define('SUCCESS', 'success');

function dump($value, $title = '')
{
    echo "<pre>$title ".print_r($value, true)."</pre>";
}

/**
 * @param string $message
 * @param string $flags  ERROR INFO SUCCESS
 * 
 * @return void
 */
function message(string $message, $flags) :void
{
    echo "<div class='$flags-message' > $message </div>";
}