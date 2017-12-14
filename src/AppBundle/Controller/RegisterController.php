<?php
/**
 * Created by PhpStorm.
 * User: bleri
 * Date: 11/15/2017
 * Time: 11:21 PM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("register")
 * Class RegisterController
 * @package AppBundle\Controller
 */
class RegisterController extends Controller
{
    /**
     * @Route("/", name="user_registration")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        $translation = $this->get('translator');

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $password = $this->get('security.password_encoder')
                    ->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);
                $user->setToken($password);

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                $this->addFlash('success', $translation->trans('user.register'));
            } catch (\Exception $exception) {
                $logger = $this->get('logger');
                $logger->error('User has not been created', $exception);
                $this->addFlash('error', $translation->trans('user.not_registered'));
            }
            return $this->redirectToRoute('homepage');
        }
        return $this->render('users/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
