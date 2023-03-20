<?php

namespace DeclensionUkrainian;

class Core
{    
    protected static $vowels = ['a', 'е', 'и', 'і', 'о', 'у', 'я', 'є', 'ї', 'ю'];
    protected static $consonants = ['б', 'в', 'г', 'ґ', 'д', 'ж', 'з', 'к', 'л', 'м', 'н', 'п', 'р', 'с', 'т', 'ф', 'х', 'ц', 'ч', 'ш', 'щ'];
    
    protected static $cases = [
        'nominative'    =>  ['називний',    'н', 'nom', 'n'],
        'genetive'      =>  ['родовий',     'р', 'gen', 'g'],
        'dative'        =>  ['давальний',   'д', 'dat', 'd'],
        'accusative'    =>  ['знахідний',   'з', 'acc', 'a'],
        'instumental'   =>  ['орудний',     'о', 'ins', 'i'],
        'locative'      =>  ['місцевий',    'м', 'loc', 'l'],
        'vocative'      =>  ['кличний',     'к', 'voc', 'v'],
    ];

    protected static $endings = [
        '1h'    =>  [
            'nominative'    =>  'а',
            'genetive'      =>  'и',
            'dative'        =>  'і',
            'accusative'    =>  'у',
            'instumental'   =>  'ою',
            'locative'      =>  'і',
            'vocative'      =>  'о',
        ],
        '1s'    =>  [
            'nominative'    =>  'я',
            'genetive'      =>  'і',
            'dative'        =>  'і',
            'accusative'    =>  'ю',
            'instumental'   =>  'ею',
            'locative'      =>  'і',
            'vocative'      =>  'е',
        ],
        '1si'    =>  [
            'nominative'    =>  'я',
            'genetive'      =>  'ї',
            'dative'        =>  'ї',
            'accusative'    =>  'ю',
            'instumental'   =>  'єю',
            'locative'      =>  'ї',
            'vocative'      =>  'є',
        ],
        '2h'    =>  [
            'nominative'    =>  '',
            'genetive'      =>  'а',
            'dative'        =>  'у',
            'accusative'    =>  'а',
            'instumental'   =>  'ом',
            'locative'      =>  'ові',
            'vocative'      =>  'е',
        ],
        '2s'    =>  [
            'nominative'    =>  '',
            'genetive'      =>  'я',
            'dative'        =>  'еві',
            'accusative'    =>  'я',
            'instumental'   =>  'ем',
            'locative'      =>  'еві',
            'vocative'      =>  'ю',
        ],
        '2si'   =>  [
            'nominative'    =>  '',
            'genetive'      =>  'я',
            'dative'        =>  'єві',
            'accusative'    =>  'я',
            'instumental'   =>  'єм',
            'locative'      =>  'єві',
            'vocative'      =>  'ю',
        ],
        '2m'   =>  [
            'nominative'    =>  '',
            'genetive'      =>  'а',
            'dative'        =>  'еві',
            'accusative'    =>  'а',
            'instumental'   =>  'ем',
            'locative'      =>  'еві',
            'vocative'      =>  'е',
        ],
        '3'   =>  [
            'nominative'    =>  '',
            'genetive'      =>  'і',
            'dative'        =>  'і',
            'accusative'    =>  '',
            'instumental'   =>  'ю',
            'locative'      =>  'і',
            'vocative'      =>  'е',
        ],
        // Чоловічи назви прикметникового типу на -ий, -ій
        'male_adjective_1'  => [
            'nominative'    =>  '',
            'genetive'      =>  'ого',
            'dative'        =>  'ому',
            'accusative'    =>  'ого',
            'instumental'   =>  'им',
            'locative'      =>  'ому',
            'vocative'      =>  '',
        ],
        // Чоловічі назви прикметникового типу на -ов, -ев (-єв), -ів (-їв), ин, -ін (-їн)
        'male_adjective_2'  => [
            'nominative'    =>  '',
            'genetive'      =>  'а',
            'dative'        =>  'у',
            'accusative'    =>  'а',
            'instumental'   =>  'им',
            'locative'      =>  'і',
            'vocative'      =>  'е',
        ],
        // Жіночі назви прикметникового типу на -ка, -ва
        'female_adjective_1'  => [
            'nominative'    =>  '',
            'genetive'      =>  'ої',
            'dative'        =>  'ій',
            'accusative'    =>  'у',
            'instumental'   =>  'ою',
            'locative'      =>  'ій',
            'vocative'      =>  '',
        ],    

    ];
    
   
    protected static function isFemale($gender): bool
    {
        return in_array($gender, ['female', 'f', 'жіноча', 'ж']);
    }

    protected static function isMale($gender): bool
    {
        return in_array($gender, ['male', 'm', 'чоловіча', 'ч']);
    }

    protected static function getNameDeclensionGroup(string $name, string $gender)
    {
        // Convert gender to lower case
        $gender = mb_strtolower($gender);

        // Жіночі прикметникового типу
        if (self::isFemale($gender)) {            
            if (in_array(mb_substr($name, -3), ['ова', 'іна', 'ька'])) {
                return 'female_adjective_1';
            }           
        }
        // Чоловічі і жіночі імена - 1 група
        $ending = mb_substr($name, -1);
        if (in_array($ending , ['а', 'я'])) {
            if ($ending == 'а') {
                return '1h';        // Перша тверда
            } else {                
                if (mb_substr($name, -2, 1) == 'і') {
                    return '1si';       // Перша м'яка з і
                } else {
                    return '1s';        // Перша м'яка
                }
            }
        }

        // Чоловічі назви  - 2 група        
        if (self::isMale($gender)) {

            $ending = mb_substr($name, -2,2);            
            if (in_array($ending, ['ий', 'ій'])) {                
                return 'male_adjective_1';
            }    

            $ending = mb_substr($name, -1);
            if (in_array($ending, self::$consonants) ) {
                if (in_array($ending, ['ж', 'ч', 'ш'])) {
                    return '2m';    // Друга мішана        
                }
                // Імена з історично м’яким "р"
                if (in_array($name, ['Ігор', 'Лазар'])) {
                    return '2s';   // Друга м'яка для імен Ігор, Лазар
                }
                return '2h';        // Друга тверда
            }
            if ($ending == 'о') {
                return '2h';        // Друга тверда
            }            
            if (in_array($ending, ['й', 'ь'])) {
                if (mb_substr($name, -2, 1) == 'і') {
                    return '2si';   // Друга м'яка з і
                }
                return '2s';        // Друга м'яка   
            }
        }

        // Жіночі імена - 3 група
        if ( self::isFemale($gender) ) { 
            $ending = mb_substr($name, -1);
            if ( in_array($ending, self::$consonants) || $ending == 'ь' ) {            
                return '3';        // Третя
            }
        }

        return null;
    }

    protected static function getCase(string $case_name): string
    {
        foreach(self::$cases as $name => $values) {
            if (in_array($case_name, $values)) {
                return $name;
            }
        }
        return null;
    }

    protected static function makeNameEnding(string $name, string $gender, string $case): string
    {
        // Find declension group
        $group = self::getNameDeclensionGroup($name, $gender);
        
        $case_ending = self::$endings[$group][$case];
        
        if (in_array($group, ['1h', '1s', '1si'])) {
            
            // Замінюємо літери 'г, к, х' на 'з, ц, с' в іменах s прізвищах в давальному і місцевому відмунку
            // if (self::isFemale($gender) && in_array($case,['dative','locative'])) {
            if (in_array($case,['dative','locative'])) {                             
                $patterns = array('/(г)(.)$/u', '/(к)(.)$/u', '/(х)(.)$/u');
                $replacements = array('з\2', 'ц\2', 'с\2');
                $name = preg_replace($patterns, $replacements, $name);
            }        
            return mb_substr($name, 0, -1) . $case_ending;

        } 
        
        if (in_array($group, ['2h', '2m'])) {            

            //  Змінюємо передостанню літеру "о" на "і" в іменах: Антін, Нестір, Ничипір, Прокіп, Сидір, Тиміш, Федір
            //  для непрямих відмінків (усі, окрім називного і кличного)
            //  але залишаемо без змін для імен: Авенір, Лаврін, Олефір
            if ( (mb_substr($name, -2, 1) == 'і') && ($case !== 'nominative') && !in_array($name, ['Авенір', 'Лаврін', 'Олефір']) ) {
                $name = preg_replace('/(і)(.)$/u', 'о\2', $name);
            }

            // Чоловічі імена на "о" в називному відмінку зберігають закінчення
            if(mb_substr($name, -1) == 'о') {
                if ($case !== 'nominative') {
                    return mb_substr($name, 0, -1) . $case_ending;
                }
                return $name;
            }            

            // Олег в клічному відмінку Олеже
            if((mb_substr($name, -1) == 'г') && ($case = 'vocative')) {
                $name = preg_replace('/(г)$/u', 'ж', $name);
            }

            return $name . $case_ending;

        } 
        
        if (in_array($group, ['2s', '2si'])) {
            // Імена з історично м’яким р (Ігор, Лазар)
            if (in_array($name, ['Ігор', 'Лазар'])) {
                return $name . $case_ending;
            }           
            if ($case !== 'nominative') {
                return mb_substr($name, 0, -1) . $case_ending;
            } else {
                return $name;
            }
        }
        
        if ($group == '3') {
            if ((mb_substr($name, -1) == 'ь') &&  (!in_array($case, ['nominative', 'accusative'])) ) {
                $name = mb_substr($name, 0, -1);
            }
            // подовження кінцевої приголоснії (окрім p, б, п, в, м, ф) в орудному відмінку
            if ($case == 'instumental') {
                if (in_array(mb_substr($name, -2,1), self::$vowels)) {
                    if (in_array(mb_substr($name, -1), ['p', 'б', 'п', 'в', 'м', 'ф'])) {
                        $name = $name . '`';
                    } else {
                        $name = $name . mb_substr($name, -1);
                    }
                }
            }            
            return $name . $case_ending;
        } 

        if ($group == 'male_adjective_1') {        
            $ending = mb_substr($name, -2,2);            
            // if (in_array($ending, ['ий', 'ій'])) {
                if (!in_array($case, ['nominative', 'vocative'])) {
                    return mb_substr($name, 0, -2) . self::$endings['male_adjective_1'][$case];
                }
                return $name;
            // }           
        }
        
        if ($group == 'female_adjective_1') {
            // if (in_array(mb_substr($name, -2,2), ['ка', 'ва'])) {
                if (!in_array($case, ['nominative', 'vocative'])) {
                    return mb_substr($name, 0, -1) . self::$endings['female_adjective_1'][$case];
                }
                return $name;
            // }
        }
    }
 
}