<?php
/**
 * User: Antoine Lamirault
 */

namespace AppBundle\Twig;

use AppBundle\Entity\Company;

class AppExtension extends \Twig_Extension
{
    public function getTests ()
    {
        return [
            new \Twig_SimpleTest('company', function ($event) { return $event instanceof Company; }),
        ];
    }
}