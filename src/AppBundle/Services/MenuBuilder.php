<?php
/**
 * Created by PhpStorm.
 * User: lp
 * Date: 27/03/2017
 * Time: 11:05
 */

namespace AppBundle\Services;


use AppBundle\Models\MenuItem;

class MenuBuilder
{
    private $menuItems;

    public function __construct()
    {
        $this->menuItems = array(
            new MenuItem('Admin', 'admin.home')
        );
    }

    public function getMenuItems(): array
    {
        return $this->menuItems;
    }

}