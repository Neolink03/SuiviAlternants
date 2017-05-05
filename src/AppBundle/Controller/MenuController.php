<?php
/**
 * Created by PhpStorm.
 * User: lp
 * Date: 27/03/2017
 * Time: 10:32
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MenuController extends Controller
{
    public function generateMenuAction($currentRoute)
    {
        $menuService = $this->get('app.menu');
        $menuItems = $menuService->getMenuItems();

        return $this->render('@App/Common/menu.html.twig', [
            "currentRoute" => $currentRoute,
            "menuItems" => $menuItems
        ]);
    }
}