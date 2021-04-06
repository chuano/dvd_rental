<?php

declare(strict_types=1);

namespace App\Framework\Form;

use App\Modules\Rental\RentalOrder\Application\CreateRentalOrder\CreateRentalOrderRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateRentalOrderType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => CreateRentalOrderRequest::class,
            ]
        );
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('movieId', HiddenType::class)
            ->add(
                'from',
                DateType::class,
                [
                    'widget' => 'single_text',
                    'format' => 'yyyy-MM-dd',
                    'input' => 'datetime_immutable'
                ]
            )
            ->add(
                'to',
                DateType::class,
                [
                    'widget' => 'single_text',
                    'format' => 'yyyy-MM-dd',
                    'input' => 'datetime_immutable'
                ]
            )
            ->add('submit', SubmitType::class);
    }
}
