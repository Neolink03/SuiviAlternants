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
            $this->addMenuItem('Admin', 'admin.home');
            $this->addMenuItem('Ajouter un utilisateur', 'admin.user.add');
        } 
        else if ($this->securityContext->isGranted('ROLE_MANAGER')) {
            $this->addMenuItem('Liste des étudiants', 'course_manager.students');
        }
        else if ($this->securityContext->isGranted('ROLE_STUDENT')) {
            $this->menuItems = [
                new MenuItem('Informations personnelles', 'student.showPersonalInformation')
            ];
        }
    }

    public function getMenuItems(): array
    {
        return $this->menuItems;
    }
    
    public function addMenuItem(string $title, string $path) {
        $this->menuItems[] = new MenuItem($title, $path);
    }

}