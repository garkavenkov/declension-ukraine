<?php

namespace DeclensionUkrainian;

use DeclensionUkrainian\Core;
use DeclensionUkrainian\GenderTrait;
use DeclensionUkrainian\DeclensionerTrait;

class Pronoun extends Core
{
    use DeclensionerTrait, GenderTrait;

    // Available name for first form
    private static $first_form = [
        'first', 'перша', 1, '1'
    ];

    // Available name for second form
    private static $second_form = [
        'second', 'друга',  2, '2'
    ];

    // Available name for third form
    private static $third_form = [
        'third', 'третя', 3, '3'
    ];

    // Available name for singular number
    private static $singular = [
        'singular', 's', 'однина', 'о'
    ];

    // Available name for plural number
    private static $plural = [
        'plural', 'p', 'множина', 'м'
    ];


    // Pronouns table
    private static $pronouns = [
        'first' => [
            'singular'  =>  [
                'nominative'    =>  'я',
                'genetive'      =>  'мене',
                'dative'        =>  'мені',
                'accusative'    =>  'мене',
                'instrumental'  =>  'мною',
                'locative'      =>  'мені',            
            ],
            'plural' => [
                'nominative'    =>  'ми',
                'genetive'      =>  'нас',
                'dative'        =>  'нам',
                'accusative'    =>  'нас',
                'instrumental'  =>  'нами',
                'locative'      =>  'нас', 
            ]
        ],
        'second' => [
            'singular'  => [
                'nominative'    =>  'ти',
                'genetive'      =>  'тебе',
                'dative'        =>  'тобі',
                'accusative'    =>  'тебе',
                'instrumental'  =>  'тобою',
                'locative'      =>  'тобі',                
            ],
            'plural' => [
                'nominative'    =>  'ви',
                'genetive'      =>  'вас',
                'dative'        =>  'вам',
                'accusative'    =>  'вас',
                'instrumental'  =>  'вами',
                'locative'      =>  'вас',                
            ]
        ],
        'third' => [
            'male'  =>  [
                'singular'  => [
                    'nominative'    =>  'він',
                    'genetive'      =>  'його',
                    'dative'        =>  'йому',
                    'accusative'    =>  'йього',
                    'instrumental'  =>  'ним',
                    'locative'      =>  'нім',                
                ],
                'plural' => [
                    'nominative'    =>  'вони',
                    'genetive'      =>  'їх',
                    'dative'        =>  'їм',
                    'accusative'    =>  'їх',
                    'instrumental'  =>  'ними',
                    'locative'      =>  'них',                
                ]

            ],
            'female' => [
                'singular'  => [
                    'nominative'    =>  'вона',
                    'genetive'      =>  'її',
                    'dative'        =>  'їй',
                    'accusative'    =>  'її',
                    'instrumental'  =>  'нею',
                    'locative'      =>  'ній',                
                ]                
            ],
            'neuter' => [
                'singular'  =>  [
                    'nominative'    =>  'воно',
                ]
            ]
        ]
    ];


    /**
     * Person form: 1st form, 2nd form, 3rd form
     *
     * @param srting $form  Person form
     * @return string
     */
    private static function getPronounForm(string $form): String
    {
        if (in_array($form, self::$first_form)) {
            return 'first';
        } else if (in_array($form, self::$second_form)) {
            return 'second';
        } else if (in_array($form, self::$third_form)) {
            return 'third';
        } else {
            throw new \Error('Cannot define pronoun form');
        }        
    }

    /**
     * Determines the number of a pronoun (singlural or plural)
     *
     * @param string $number
     * @return String
     */
    private static function getPronounNumber(string $number): String
    {
        if (in_array($number, self::$singular)) {
            return 'singular';
        } else if (in_array($number, self::$plural)) {
            return 'plural';
        } else {
            throw new \Error('Cannot define pronoun number');
        }
    }

    /**
     * Determines the gender of a pronoun
     *
     * @param string $gender
     * @return String
     */
    private static function getPronounGender(string $gender): String
    {
        if (self::isMale($gender)) {
            return 'male';
        } else if (self::isFemale($gender)) {
            return 'female';
        } else if (self::isNeuter($gender)) {
            return 'neuter';
        } else {
            throw new \Error('Cannot define pronoun gender');
        }
    }

    /**
     * Check wheather number is singular
     *
     * @param string $number    Person number
     * @return boolean
     */
    private static function isSingular(string $number): bool
    {
        return in_array($number, self::$singular);
    }

    /**
     * Check wheather number is plural
     *
     * @param string $number    Person number
     * @return boolean
     */
    private static function isPlural(string $number): bool
    {
        return in_array($number, self::$plural);
    }

    /**
     * Declension pronoun
     *
     * @param array $pronoun
     * @param string $case      Case (nominitive, genetive, etc.)
     * @return String
     */
    private static function declension(array $pronoun, string $case): String
    {
        $form = self::getPronounForm($pronoun['form']);
        $number = self::getPronounNumber($pronoun['number']);
        
        if ($form == 'third') {
            $gender = self::getPronounGender($pronoun['gender']);
        }

        if (in_array($form, ['first', 'second'])) {
            return self::$pronouns[$form][$number][$case];            
        } 

        if ($form == 'third') {

            if (in_array($gender, ['male','neuter'])) {
                
                if (self::isNeuter($gender) && self::isSingular($number) && ($case == 'nominative')) {
                    return self::$pronouns['third'][$gender][$number][$case];
                }

                if (self::isSingular($number)) {
                    return self::$pronouns['third']['male']['singular'][$case];
                }

                if (self::isPlural($number)) {
                    return self::$pronouns['third']['male']['plural'][$case];
                }
                
            } 
            
            if ($gender == 'female') {
                
                if (self::isSingular($number)) {
                    return self::$pronouns['third']['female']['singular'][$case];
                } 
                // Female's plural third form use male's plural third form
                if (self::isPlural($number)){
                    return self::$pronouns['third']['male']['plural'][$case];
                } 
                
            } 

        }

        return null;
    }
}
