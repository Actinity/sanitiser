<?php
namespace Actinity\Sanitiser;

class Sanitiser
{

    public static function clean($input)
    {
        $input = trim($input, " \t\n\r\0\x0B\xC2\xA00\xE2\x80\x8B0\xC2\xAD");

        if (substr_count($input,"@") != 1) {
            return false;
        }

        if (!preg_match("!(.+@.+\..+)!",$input,$matches)) {
            return false;
        }

        $input = $matches[1];

        if (preg_match("!\.@!",$input)) {
            return false;
        }

        $first = substr($input,0,1);
        $last = substr($input,-1,1);

        if (
            $first == "'" && $last == "'" ||
            $first == '"' && $last == '"' ||
            $first == '[' && $last == ']' ||
            $first == "<" && $last == ">" ||
            $first == '(' && $last == ')'
        ) {
            $input = substr($input,1,-1);
        }

        if (in_array($last,[',',';',"."])) {
            $input = substr($input,0,-1);
        }

		$input = preg_replace("![\s\t\n\r\x0B\xC2\xA0\xE2\x80\x8B\xC2\xAD]!","",$input);

        if (preg_match("!^[a-zA-Z0-9\.]+@[a-zA-Z0-9\.]+$!",$input)) {
            $test = strtolower(str_replace(".","",$input));
            if (preg_match("!^[a-z0-9]+@[a-z0-9]+$!",$test)) {
                return $input;
            } else {
                return false;
            }
        }

        if (preg_match("!<(.+@.+)>!",$input,$matches)) {
            $input = $matches[1];
        }

        $input = trim(str_replace("mailto:","",$input));

        if (preg_match("!\s!",$input)) {
            return false;
        }

        if (!preg_match("!\.([a-zA-Z0-9-]{2,30})$!",$input)) {
            return false;
        }

        return $input;
    }
}