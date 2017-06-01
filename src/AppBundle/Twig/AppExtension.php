<?php
/**
 * User: Antoine Lamirault
 */

namespace AppBundle\Twig;

use AppBundle\Entity\AfterCourse;
use AppBundle\Entity\Company;
use AppBundle\Entity\TransitionCondition;
use AppBundle\Entity\StudentCountCondition;
use AppBundle\Entity\DatetimeCondition;

class AppExtension extends \Twig_Extension
{
    public function getTests ()
    {
        return [
            new \Twig_SimpleTest('company', function ($event) { return $event instanceof Company; }),
            new \Twig_SimpleTest('afterCourse', function ($event) { return $event instanceof AfterCourse; }),
            new \Twig_SimpleTest('StudentCountCondition', function ($event) { return $event instanceof StudentCountCondition; }),
            new \Twig_SimpleTest('DatetimeCondition', function ($event) { return $event instanceof DatetimeCondition; }),
        ];
    }
}