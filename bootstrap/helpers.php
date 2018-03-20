<?php

use Stevebauman\Purify\Facades\Purify;

/*
|--------------------------------------------------------------------------
| Application helper functions
|--------------------------------------------------------------------------
*/

if (!function_exists('convert_to_xml')) {
    function convert_to_xml($string)
    {
        libxml_use_internal_errors(true);

        $xml = new SimpleXMLElement($string, LIBXML_NOCDATA);

        if (!$xml) {
            $error = libxml_get_last_error();

            if ($error && $error->message) {
                $error->message = trim($error->message);

                throw new RuntimeException($error->message);
            }
        }

        return $xml;
    }
}

if (!function_exists('purify')) {
    function purify($content)
    {
        if (empty($content)) {
            return null;
        }

        return Purify::clean($content);
    }
}
