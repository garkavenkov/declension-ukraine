<?php

namespace DeclensionUkrainian;

trait Declensioner
{
    public static function inNominative($name)
    {   
        return self::declension($name, case: 'nominative');
    }
    
    public static function inGenetive($name)
    {        
        return self::declension($name, case: 'genetive');
    }

    public static function inDative($name)
    {        
        return self::declension($name, case: 'dative');
    }

    public static function inAccusative($name)
    {        
        return self::declension($name, case: 'accusative');
    }

    public static function inInstrumental($name)
    {        
        return self::declension($name, case: 'instrumental');
    }

    public static function inLocative($name)
    {        
        return self::declension($name, case: 'locative');
    }

    public static function inVocative($name)
    {        
        return self::declension($name, case: 'vocative');
    }


    public static function allCases($name, string $lang='en')
    {
        $cases = array();
        foreach(array_keys(parent::$cases) as $case) {
            $res = self::declension($name, case: $case);            
            if ($lang=='ua') {
                $cases[parent::$cases[$case][0]] = $res;
            } else {
                $cases[$case] = $res;
            }
        }
        return $cases;
    }
}