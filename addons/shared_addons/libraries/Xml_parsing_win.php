<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of xml_parsing_win
 *
 * @author Nuryanto
 */
class Xml_parsing_win {
    //put your code here
    function element_set($element_name, $xml, $content_only = false) {
        if ($xml == false) {
            return false;
        }
        $found = preg_match_all('#<' . $element_name . '(?:\s+[^>]+)?>' .
                '(.*?)</' . $element_name . '>#s', $xml, $matches, PREG_PATTERN_ORDER);
        if ($found != false) {
            if ($content_only) {
                return $matches[1];  
            } else {
                return $matches[0];  
            }
        }
        return false;
    }

    function value_in($element_name, $xml, $content_only = true) {
        if ($xml == false) {
            return false;
        }
        $found = preg_match('#<' . $element_name . '(?:\s+[^>]+)?>(.*?)' .
                '</' . $element_name . '>#s', $xml, $matches);
        if ($found != false) {
            if ($content_only) {
                return $matches[1];  
            } else {
                return $matches[0];  
            }
        }
        return false;
    }   
    
}

?>
