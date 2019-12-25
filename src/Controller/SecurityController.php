<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\UserRegister;
use App\Exceptions\SecurityException;
use App\Form\UserRegisterFormType as UserForm;
use App\Services\SecurityService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends WebController
{
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('security/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError()
            ]);
    }

    public function register(
        UserRegister $userRegister,
        SecurityService $service
    ): Response {
        $form = $this->createForm(UserForm::class);
        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid() && $form[UserForm::AGREE_TERMS]->getData()) {
            $userRegister->set(
                $form[UserForm::EMAIL]->getData(),
                $form[UserForm::FULL_NAME]->getData(),
                $form[UserForm::PLAIN_PASSWORD]->getData()
            );

            $service->register($userRegister);

            return $this->redirectToRouteWithSuccess(
                'Success! We sent a mail to you, so check it and confirm your email.'
            );
        }

        return $this->render('security/sign_up.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    public function confirm(SecurityService $service)
    {
        if (empty($token = $this->request->get('token'))) {
            return $this->fallToRouteWithError('You have no token. Contact to admin.');
        }

        try {
            $service->confirm($token);
        } catch (SecurityException $exception) {
            return $this->fallToRouteWithError($exception->getMessage());
        }

        return $this->redirectToRouteWithSuccess('Success! Now you can login!');
    }

    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }
}
