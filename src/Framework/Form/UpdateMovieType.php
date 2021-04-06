<?php

declare(strict_types=1);

namespace App\Framework\Form;

use App\Modules\Rental\RentalOrder\Application\CreateRentalOrder\CreateRentalOrderRequest;
use App\Shared\Movie\Application\CreateMovie\CreateMovieRequest;
use App\Shared\Movie\Application\UpdateMovie\UpdateMovieRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateMovieType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => UpdateMovieRequest::class,
            ]
        );
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('synopsis', TextType::class)
            ->add('year', NumberType::class)
            ->add('stock', NumberType::class)
            ->add('submit', SubmitType::class);
    }
}
