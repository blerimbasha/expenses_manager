<?php
/**
 * Created by PhpStorm.
 * User: bleri
 * Date: 11/16/2017
 * Time: 11:28 PM
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class UserController
 * @package AppBundle\Controller
 * @Route("users")
 */
class UserController extends Controller
{
    /**
     * @Route("/", name="users")
     */
    public function showActions()
    {
        return $this->render('admin/show.html.twig');
    }
}
