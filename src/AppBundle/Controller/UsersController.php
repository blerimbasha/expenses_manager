<?php
/**
 * Created by PhpStorm.
 * User: bleri
 * Date: 11/15/2017
 * Time: 11:21 PM
 */

namespace AppBundle\Controller;



use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UsersController extends Controller
{
    /**
     * @Route("newuser", name="new_user")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction()
    {
        return $this->render('users/new.html.twig');
    }
}
