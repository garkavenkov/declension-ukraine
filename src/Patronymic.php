<?php

namespace DeclensionUkrainian;

use DeclensionUkrainian\Core;
use DeclensionUkrainian\GenderTrait;

class Patronymic extends Core
{
    use GenderTrait;

    public static function getPatronymic(string $father_name, string $gender): string
    {
        $patronymic = '';

        if (self::isMale($gender)) {
            switch ($father_name){
                case 'Лука':
                    $patronymic = 'Лукич';
                    break;
                case 'Сава':
                    $patronymic = 'Савич';
                    break;
                case 'Кузьма':
                    $patronymic = 'Кузьмич';
                    break;
                case 'Хома':
                    $patronymic = 'Хомич';
                    break;
                case 'Яків':
                    $patronymic = 'Якович';
                    break;
                case 'Ілля':
                    $patronymic = 'Ілліч';
                    break;
                case 'Григорій':
                    $patronymic = 'Григорович';
                    break;
                case 'Микола':
                    $patronymic = 'Миколайович';
                    break;
                case 'Ігор':
                    $patronymic = 'Ігорьович';
                    break;
                case mb_substr($father_name, -1) == 'о':
                    // Михайло - МихайлоВИЧ
                    $patronymic = $father_name . 'вич';
                    break;
                default:
                    $patronymic =$father_name . 'ович';
            }

        } else if (self::isFemale($gender)) {
            switch ($father_name){               
                case 'Кузьма':
                    $patronymic = 'Кузьмівна';
                    break;                
                case 'Яків':
                    $patronymic = 'Яківна';
                    break;
                case 'Ілля':
                    $patronymic = 'Іллівна';
                    break;
                case 'Григорій':
                    $patronymic = 'Григорівна';
                    break;
                case 'Микола':
                    $patronymic = 'Миколаївна';
                    break;                
                case mb_substr($father_name, -1) == 'о':
                    // Михайло - Михайлівна
                    $patronymic = mb_substr($father_name, 0, mb_strlen($father_name)-1) . 'івна';
                    break;
                case mb_substr($father_name, -1) == 'й':
                    // Юрій - Юріївна
                    $patronymic = mb_substr($father_name, 0, mb_strlen($father_name)-1) . 'ївна';
                    break;
                default:
                    $patronymic =$father_name . 'івна';
            }             
        }
        return $patronymic;
    }

}