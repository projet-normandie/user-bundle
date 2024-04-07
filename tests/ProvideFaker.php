<?php

declare(strict_types=1);

namespace ProjetNormandie\UserBundle\Tests;

use Faker\Factory;
use Faker\Generator;

trait ProvideFaker
{
    public static function faker(): Generator
    {
        static $factory = null;

        if (null === $factory) {
            $factory = Factory::create();
        }

        return $factory;
    }
}
