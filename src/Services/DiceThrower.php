<?php

namespace App\Services;

class DiceThrower
{
    public function rollDices(int $number, int $faces) :array
    {
        if ($number > 0 && $face > 1) {
            for ($i = 1; $i <= $number; $i++) {
                $alea[] = rand(1, $faces);
            }
        }

        return $alea;
    }

    public function rollTwenty(int $number) :array
    {
        if ($number > 0 && $face > 1) {
            for ($i = 1; $i <= $number; $i++) {
                $alea[] = rand(1, 20);
            }
        }
        return $alea;
    }

    public function rollHundred(int $number) :array
    {
        if ($number > 0 && $face > 1) {
            for ($i = 1; $i <= $number; $i++) {
                $alea[] = rand(1, 100);
            }
        }
        return $alea;
    }
}
