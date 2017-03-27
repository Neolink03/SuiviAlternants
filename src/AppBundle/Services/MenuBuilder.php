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
        $this->build();
    }

    private function build()
    {
        if ($this->securityContext->isGranted('ROLE_ADMIN')) {
            $this->menuItems = array(
                new MenuItem('Admin', 'admin.home')
            );
        }
    }

    public function getMenuItems(): array
    {
        return $this->menuItems;
    }

}