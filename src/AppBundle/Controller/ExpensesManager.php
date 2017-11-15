<?php
/**
 * Created by PhpStorm.
 * User: bleri
 * Date: 11/15/2017
 * Time: 10:37 PM
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ExpensesManager extends Controller
{
    /**
     * @Route("/thismonth", name="this_month")
     */
    public function thisMonthAction()
    {
        return $this->render('expenses/thismonth.html.twig');
    }

    /**
     * @Route("/lastmonth", name="last_month")
     */
    public function lastMonthAction()
    {
        return $this->render('expenses/lastmonth.html.twig');
    }
}
