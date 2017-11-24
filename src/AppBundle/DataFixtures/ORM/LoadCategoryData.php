<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Category;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Created by PhpStorm.
 * User: bleri
 * Date: 11/22/2017
 * Time: 9:45 PM
 */
class LoadCategoryData implements FixtureInterface
{
    public function load(ObjectManager $objectManager)
    {
        $categories = [
            'Education',
            'Entertainment',
            'Family',
            'Food & Beverage',
            'Friends',
            'Lover',
            'Gifts',
            'Health & Fitness',
            'Investment',
            'Others',
            'Shopping',
            'Transport',
            'Travel'
        ];
        for ($i = 0; $i < count($categories); $i++) {
            $category = new Category();
            $category->setName($categories[$i]);

            $objectManager->persist($category);
            $objectManager->flush();
        }
    }
}