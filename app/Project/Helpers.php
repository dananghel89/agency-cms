<?php

/**
 * Get option value by name.
 */
if (! function_exists('option')) {
    function option($name, $decode = false)
    {
        $value = data_get(app('sb.options'), $name);

        return ($decode && $value) ? (array) json_decode($value, true) : $value;
    }
}

/**
 * Merges user defined arguments into defaults array.
 */
if (! function_exists('parse_args')) {
    function parse_args($args, $defaults = [])
    {
        if (is_object($args)) {
            $parsed_args = get_object_vars($args);
        } elseif (is_array($args)) {
            $parsed_args = &$args;
        } else {
            parse_str((string) $args, $parsed_args);
        }

        if (is_array($defaults) && $defaults) {
            return array_merge($defaults, $parsed_args);
        }

        return $parsed_args;
    }
}
