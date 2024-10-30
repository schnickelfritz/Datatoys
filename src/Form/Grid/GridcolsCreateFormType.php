<?php

declare(strict_types=1);

namespace App\Form\Grid;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class GridcolsCreateFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('names', TextareaType::class, [
                'label' => 'grid.col.label.names',
                'attr' => [
                    'focus' => true,
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'grid.col.create.submitbutton',
            ])
        ;
    }
}
