<?php

namespace DeclensionUkrainian;

use DeclensionUkrainian\Core;
use DeclensionUkrainian\Declensioner;

class Anthroponym extends Core
{
    use Declensioner;
   
    private static $patronymic = [
        'male' => [
            'nominative'    =>  '',
            'genetive'      =>  'а',
            'dative'        =>  'у',
            'accusative'    =>  'а',
            'instumental'   =>  'ем',
            'locative'      =>  'і',
            'vocative'      =>  'у',
        ],
        'female' => [
            'nominative'    =>  '',
            'genetive'      =>  'и',
            'dative'        =>  'і',
            'accusative'    =>  'у',
            'instumental'   =>  'ою',
            'locative'      =>  'і',
            'vocative'      =>  'о',
        ],
    ];


    private static function makePatronimycEnding(string $patronymic, string $gender, string $case): string
    {        
        if (self::isMale($gender)) {
            return $patronymic . self::$patronymic['male'][$case];
        }
        if (self::isFemale($gender)) {
            if ($case == 'nominative') {
                return $patronymic;
            }
            return mb_substr($patronymic, 0, -1) . self::$patronymic['female'][$case];
        }
        return null;
    }

    private static function makeSurnameEnding(string $surname, string $gender, string $case): string
    {        
        // Жіночі прізвища
        if (self::isFemale($gender)) {
            if ( in_array(mb_substr($surname, -1), self::$consonants) || (mb_substr($surname, -1) == 'о') ) {
               return $surname;
            }
            if (mb_substr($surname, -1) == 'а') {
                if (!in_array($case, ['nominative', 'vocative'])) {
                    return mb_substr($surname, 0, -1) . self::$endings['female_adjective_1'][$case];
                }
                return $surname;
            }
            return self::makeNameEnding($surname, $gender, $case);
        } 

        // Чоловічі прізвища
        if (self::isMale($gender)) {
            
            $ending = mb_substr($surname, -4,4);
            // Прізвища на -бель
            if ($ending == 'бель') {
                if ($case !== 'nominative') {                    
                    $surname = preg_replace('/(бель)$/u',  'бел', $surname);
                    return $surname . self::$endings['2s'][$case];
                }
                return $surname;
            }           

            // Прізвища на -єць, -ель, -ідь
            $ending = mb_substr($surname, -3,3);
            if (in_array($ending, ['ець', 'єць', 'ель', 'ідь'])) {
                if ($case !== 'nominative') {
                    $patterns = array('/(ець)$/u', '/(єць)$/u', '/(ель)$/u', '/(ідь)$/u');
                    $replacements = array('ьц', 'йц', 'л', 'ед');
                    $surname = preg_replace($patterns, $replacements, $surname);             
                    return $surname . self::$endings['2s'][$case];
                }
                return $surname;
            }            
            // Прізвища на -сел (Бусел)
            if ($ending == 'сел') {
                if ($case !== 'nominative') {                    
                    $surname = preg_replace( '/(сел)$/u', 'сл', $surname);
                    return $surname . self::$endings['2h'][$case];
                }
                return $surname;
            }           
          
            
            $ending = mb_substr($surname, -2);
            // Прізвища на -ий, -ій
            if (in_array($ending, ['ий', 'ій'])){
                if ($case !== 'nominative') {
                    return mb_substr($surname, 0, -2) . self::$endings['male_adjective_1'][$case];
                }
                return $surname;  
            }

            // Прізвища на -ов, -ев (-єв), -ів (-їв), ин, -ін (-їн)
            if (in_array($ending, ['ов', 'ев', 'єв', 'ів', 'їв', 'ин', 'ін', 'їн'])) {                
                if ($case !== 'nominative') {                    
                    $patterns = array('/(лів)$/u', '/(опів)$/u', '/(чів)$/u');
                    $replacements = array('лев', 'опов', 'чев');
                    $surname = preg_replace($patterns, $replacements, $surname);                    
                    return $surname . self::$endings['male_adjective_2'][$case];
                }
                return $surname;
            }

            // Прізвища на -ей (Соловей)
            if ($ending == 'ей') {
                if ($case !== 'nominative') {
                    $surname = preg_replace('/(ей)$/u', '`', $surname);
                    return $surname . self::$endings['2si'][$case];
                }
                return $surname;
            }

            // Прізвища на -ок, -ор :  (Свекор, Моток)            
            if (in_array($ending, ['ок', 'ор'])) {
                if ($case !== 'nominative') {
                    $surname = preg_replace('/(.)(.)$/u', '\2', $surname);
                    return $surname . self::$endings['2h'][$case];
                }
                return $surname;
            }

            // Прізвища на -ир (М'яка р)
            if ($ending == 'ир') {
                return $surname . self::$endings['2s'][$case];
            }

            // Прізвища на -ьо (Іваньо, Дідуньо)
            if ($ending == 'ьо') {
                if ($case !== 'nominative') {                 
                    return mb_substr($surname, 0, -1) . self::$endings['2s'][$case];
                }
                return $surname;
            }

            // Відмінюємо прізвище за загальними правилами
            return self::makeNameEnding($surname, $gender, $case);


        }
        return null;
    }

    private static function declension($anthroponym, $case)
    {
        if (!isset($anthroponym['gender'])) {            
            die("Відсутня стать особи\n");
        }
        $gender = $anthroponym['gender'];

        $surname = '';
        $name = '';
        $patronymic = '';

        if (isset($anthroponym['surname'])) {
            $surname = self::makeSurnameEnding($anthroponym['surname'], $gender, $case);
        }

        if (isset($anthroponym['name'])) {
            $name = self::makeNameEnding($anthroponym['name'], $gender, $case);
        }
        
        if (isset($anthroponym['name'])) {
            $patronymic = self::makePatronimycEnding($anthroponym['patronymic'], $gender, $case);
        }
        
        return ($surname ? $surname . ' ' : '') . ( $name ? $name . ' ' : '') . ($patronymic ? $patronymic : '');
    }   
}