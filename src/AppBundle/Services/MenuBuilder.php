<?php
/**
 * Created by PhpStorm.
 * User: lp
 * Date: 27/03/2017
 * Time: 11:05
 */

namespace AppBundle\Services;

use AppBundle\Models\MenuItem;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class MenuBuilder
{
    private $securityContext;
    private $menuItems;

    public function __construct(AuthorizationChecker $securityContext)
    {
        $this->securityContext = $securityContext;
        $this->menuItems = [];
        $this->build();
    }

    private function build()
    {
        if ($this->securityContext->isGranted('ROLE_ADMIN')) {
            $this->menuItems = [
                new MenuItem('Admin', 'admin.home')
            ];
        } 
        else if ($this->securityContext->isGranted('ROLE_MANAGER')) {
            $this->menuItems = [
                new MenuItem('Liste des étudiants', 'course_manager.students')
            ];
        }
    }

    public function getMenuItems(): array
    {
        return $this->menuItems;
    }

}