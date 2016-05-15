<?php

namespace Geekbot;

class Utils{
    
    public function startsWith($haystack, $needle) {
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
    }

    public function xml_attribute($object, $attribute) {
        if (isset($object[$attribute])) {
            return (string)$object[$attribute];
        }
    }
    
    public function calculateLevel($messages) {
        $total = 0;
        $levels = [];
        for ($i = 1; $i < 100; $i++) {
            $total += floor($i + 300 * pow(2, $i / 7.0));
            $levels[] = floor($total / 16);
        }
        $level = 1;
        foreach ($levels as $l) {
            if ($l < $messages) {
                $level++;
            } else {
                break;
            }
        }
        return $level;
    }
}