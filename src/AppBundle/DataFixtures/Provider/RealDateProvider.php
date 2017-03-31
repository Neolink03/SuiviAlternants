<?php
/**
 * Created by PhpStorm.
 * User: lp
 * Date: 29/03/2017
 * Time: 17:06
 */

namespace AppBundle\DataFixtures\Provider;

use Faker\Provider\Base as BaseProvider;

class RealDateProvider extends BaseProvider
{
    public static function realDate(string $format)
    {
        return date($format, mt_rand(0, (new \DateTime())->getTimestamp()));
    }
}