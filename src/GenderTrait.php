<?php

namespace DeclensionUkrainian;

trait GenderTrait
{
    protected static function isFemale($gender): bool
    {
        return in_array($gender, ['female', 'f', 'жіноча', 'ж']);
    }

    protected static function isMale($gender): bool
    {
        return in_array($gender, ['male', 'm', 'чоловіча', 'ч']);
    }

    protected static function isNeuter($gender): bool
    {
        return in_array($gender, ['neuter', 'n', 'середній', 'с']);
    }
}
