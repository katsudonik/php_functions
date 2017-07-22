<?php

function getUrls($filePath, $regex = '/^(.+|\-) (.+|\-) (.+|\-) (\[(.+)\]|\-) ("(.+)"|\-) ([0-9]+|\-) ("(.+)"|\-) ("(.+)"|\-)$/'){
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $urls = array();
    foreach($lines as $line){
        preg_match($regex, $line, $matches);
        $urls[] = $matches[10];
    }
    return $urls;
}
