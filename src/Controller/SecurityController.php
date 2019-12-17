<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\UserRegisterFormType as UserForm;
use App\Security\TokenGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/sign-up", name="app_sign_up")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, TokenGenerator $tokenGenerator): Response
    {
        $form = $this->createForm(UserForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $form[UserForm::AGREE_TERMS]->getData()) {
            $user = new User();
            $user->setEmail($form[UserForm::EMAIL]->getData());
            $user->setFullName($form[UserForm::FULL_NAME]->getData());
            $user->setConfirmToken($tokenGenerator->getRandomSecureToken());
            $user->setPassword($passwordEncoder->encodePassword($user, $form[UserForm::PLAIN_PASSWORD]->getData()));

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Success! We sent a mail to you, so check it and confirm your email.');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/sign_up.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }
}
