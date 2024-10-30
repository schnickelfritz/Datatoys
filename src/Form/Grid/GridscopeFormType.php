<?php

declare(strict_types=1);

namespace App\Form\Grid;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class GridscopeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'grid.scope.label.name',
                'required' => true,
                'attr' => [
                    'focus' => true,
                ],
                'constraints' => [
                    new NotBlank(['message' => 'The Name must not be empty!']),
                ],
            ])
            ->add('scopeKey', TextType::class, [
                'label' => 'grid.scope.label.key',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'The Name must not be empty!']),
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'grid.scope.label.description',
                'required' => false,
                'attr' => [
                    'placeholder' => 'optional',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'grid.scope.create.submitbutton',
            ])
        ;
    }
}
