<?php

/**
 * Concat directory and files into local path
 * 
 * @param array list of paths
 */
function join_path(): string
{
    $paths = func_get_args();
    $result = $paths[0];

    for ($i = 1; $i < count($paths); $i++) {
        $result .= DIRECTORY_SEPARATOR . $paths[$i];
    }

    return $result;
}

/**
 * Get the path to the root directory of the web server
 */
function get_server_root(): string
{
    return dirname(__DIR__);
}
