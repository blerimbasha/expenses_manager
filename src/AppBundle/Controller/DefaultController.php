<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        if ($this->get('security.context')->isGranted('ROLE_USER')) {
            return $this->render('users/index.html.twig');
        } else {
           if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
              return $this->render('admin/index.html.twig');
           } else {
               return $this->redirectToRoute('login');
           }
        }

    }
}
