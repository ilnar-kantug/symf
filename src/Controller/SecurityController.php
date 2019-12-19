<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Event\UserRegisteredEvent;
use App\Form\UserRegisterFormType as UserForm;
use App\Repository\UserRepository;
use App\Security\TokenGenerator;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends BaseController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('security/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError()
            ]);
    }

    /**
     * @Route("/sign-up", name="app_sign_up")
     */
    public function register(
        UserPasswordEncoderInterface $passwordEncoder,
        TokenGenerator $tokenGenerator,
        EventDispatcherInterface $eventDispatcher
    ): Response {
        $form = $this->createForm(UserForm::class);
        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid() && $form[UserForm::AGREE_TERMS]->getData()) {
            $user = new User();
            $user->setEmail($form[UserForm::EMAIL]->getData());
            $user->setFullName($form[UserForm::FULL_NAME]->getData());
            $user->setConfirmToken($tokenGenerator->getRandomSecureToken());
            $user->setPassword($passwordEncoder->encodePassword($user, $form[UserForm::PLAIN_PASSWORD]->getData()));

            $this->em->persist($user);
            $this->em->flush();

            $eventDispatcher->dispatch(new UserRegisteredEvent($user), UserRegisteredEvent::NAME);

            return $this->redirectToRouteWithSuccess(
                'Success! We sent a mail to you, so check it and confirm your email.'
            );
        }

        return $this->render('security/sign_up.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/confirm", name="app_confirm_email")
     */
    public function confirm(UserRepository $userRepository)
    {
        if (empty($token = $this->request->get('token'))) {
            return $this->fallToRouteWithError('You have no token. Contact to admin.');
        }
        $user = $userRepository->findOneBy([
            User::ATTR_CONFIRM_TOKEN => $token
        ]);

        if ($user === null) {
            return $this->fallToRouteWithError('No users for to this token. Contact to admin.');
        }

        $user->setConfirmToken(null);
        $this->em->flush();

        return $this->redirectToRouteWithSuccess('Success! Now you can login!');
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }
}
