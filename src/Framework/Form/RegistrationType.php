<?php

declare(strict_types=1);

namespace App\Framework\Form;

use App\Modules\Rental\User\Application\Registration\RegistrationRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
           'data_class' => RegistrationRequest::class,
       ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class)
            ->add('firstSurname', TextType::class)
            ->add('secondSurname', TextType::class)
            ->add('address', TextType::class)
            ->add('number', TextType::class)
            ->add('city', TextType::class)
            ->add('zipCode', TextType::class)
            ->add('state', TextType::class)
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class)
            ->add('submit', SubmitType::class);
    }
}
