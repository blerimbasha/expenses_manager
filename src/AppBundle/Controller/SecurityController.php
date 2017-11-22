<?php
/**
 * Created by PhpStorm.
 * User: bleri
 * Date: 11/15/2017
 * Time: 11:01 PM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use AppBundle\Form\ResetPasswordType;
use AppBundle\Form\ResetType;
use AppBundle\Service\Mail;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     * @param AuthenticationUtils $authUtils
     * @return \Symfony\Component\HttpFoundation\Response
     * @internal param Request $request
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * @param Request $request
     * @param Mail $mail
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/reset-password", name="reset_password")
     */
    public function resetAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(ResetType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository(User::class)->findOneBy(['email' => $user->getEmail()]);
            if (!$user) {
                $form->get('email')->addError(new FormError('user.email_not_exist'));
            } else {
                $user->setToken(md5($user->getId(). $user->getPassword()));
                $em->persist($user);
                $em->flush();
                $mail = $this->container->get('app.message_generator');
                $mail->send(
                    'reset_password',
                        $user->getEmail(),
                        $this->renderView('email/reset_password_link.html.twig', [
                            'token' => $user->getToken(),
                            'user' => $user
                        ])
                );
                return $this->redirectToRoute('login');
            }
        }

        return $this->render('security/reset_password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param User|null $user
     * @param UserPasswordEncoder $encoder
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/new-password/{token}", name="new_password")
     */
    public function setnewPasswordAction(Request $request, User $user)
    {
        if (!$user) {
            return $this->redirectToRoute('login');
        }
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $data = $form->getData();
            $password = $data->getNewPassword();
            $encoder = $this->container->get('security.password_encoder')
                ->encodePassword($user, $password);
            $user->setPassword($encoder);
            $user->eraseCredentials();
            try {
                $em->persist($user);
                $em->flush();
                $this->addFlash('success', 'password_changed_success');
            } catch (\Exception $exception) {
                $this->addFlash('error', 'password_not_changed');
            }
            return $this->redirectToRoute('homepage');
        }
        return $this->render('security/reset_set_password_form.html.twig',
            ['form' => $form->createView()]
        );
    }
}
