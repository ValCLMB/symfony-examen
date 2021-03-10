<?php

namespace App\Services;

use App\Entity\Character;
use App\Services\DiceThrower;

class ActionResolver extends DiceThrower
{
    public function attack(Character $attacker, Character $defender)
    {
        $attackTest = rollHundred(1);

        if ($attackTest>$attacker->getStrength()) {
            return;
        }

        $defendTest = rollHundred(1);

        if ($defendTest>$defender->getDefense()) {
            return;
        }

        $damage = rollTwenty(6);

        return $damage;
    }
}
