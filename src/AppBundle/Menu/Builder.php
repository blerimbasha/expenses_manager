<?php
/**
 * Created by PhpStorm.
 * User: bleri
 * Date: 11/8/2017
 * Time: 10:56 PM
 */
namespace AppBundle\Menu;

use Knp\Menu\MenuFactory;

class Builder
{
    public function mainMenu(MenuFactory $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class','nav navbar-nav');
        $menu->addChild('This Month', ['route' => 'this_month']);
        $menu->addChild('Last Month', ['route' => 'last_month']);
        $menu->addChild('Login', ['route' => 'login']);
        $menu->addChild('Register', ['route' => 'new_user']);
        return $menu;
    }
}
