<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserRegisterFormType extends AbstractType
{
    public const FULL_NAME = 'fullName';
    public const EMAIL = 'email';
    public const PLAIN_PASSWORD = 'plainPassword';
    public const AGREE_TERMS = 'agreeTerms';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(self::FULL_NAME, TextType::class, [
                'label' => 'Your Full Name',
            ])
            ->add(self::EMAIL)
            ->add(self::PLAIN_PASSWORD, RepeatedType::class, [
                'mapped' => false,
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Retype Password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Choose a password'
                    ]),
                    new Length([
                        'min' => 5,
                        'minMessage' => 'At least 5 letters'
                    ])
                ]
            ])
            ->add(self::AGREE_TERMS, CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You have to agree with terms'
                    ])
                ],
                'label' => 'I agree to the terms of service',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
