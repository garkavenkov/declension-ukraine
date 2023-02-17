<?php

namespace DeclensionUkrainian;

use DeclensionUkrainian\Core;
use DeclensionUkrainian\Declensioner;

class Toponym extends Core
{
    use Declensioner;
    
    private static function declension($toponym, $case)
    {
        if (!isset($toponym['type'])) {            
            die("Відсутній тип топонім\n");
        }
        
        if (!isset($toponym['name'])) {            
            die("Відсутня назва топоніма\n");
        }
        
        if ($toponym['type'] == 'район') {
            $gender = 'male';
        } else if ($toponym['type'] == 'область') {
            $gender = 'female';
        }

        $name = '';
        $type = '';

        $name = self::makeNameEnding($toponym['name'], $gender, $case);
        $type = self::makeNameEnding($toponym['type'], $gender, $case);
        
        return ($name ? $name . ' ' : '') . ($type ? $type : '');
    }  
}