<?php

declare(strict_types=1);

namespace App\Form\Grid;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class GridContentCreateFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $choicesOptions = [
            'grid.content.create.option.update' => 'UPDATE',
            'grid.content.create.option.allow_new_columns' => 'ALLOW_NEW_COLUMNS',
            'grid.content.create.option.skip_same_name_columns' => 'SKIP_SAME_NAME_COLUMNS',
            'grid.content.create.option.concat_same_name_columns' => 'CONCAT_SAME_NAME_COLUMNS',
        ];
        $choicesSeparator = [
            'grid.content.create.separator.tab' => "\t",
            'grid.content.create.separator.comma' => ',',
            'grid.content.create.separator.semicolon' => ';',
            'grid.content.create.separator.pipe' => '|',
        ];
        $firstChoiceSeparator = current($choicesSeparator);

        $builder
            ->add('content', TextareaType::class, [
                'label' => 'grid.content.label.content',
                'attr' => [
                    'focus' => true,
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Content must not be empty!']),
                ],
            ])
            ->add('options', ChoiceType::class, [
                'label' => 'grid.content.label.options',
                'choices' => $choicesOptions,
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'placeholder' => false,
                'attr' => [
                ],
            ])
            ->add('separator', ChoiceType::class, [
                'label' => 'grid.content.label.separator',
                'choices' => $choicesSeparator,
                'data' => $firstChoiceSeparator,
                'multiple' => false,
                'expanded' => true,
                'required' => false,
                'placeholder' => false,
                'attr' => [
                ],
            ])
            ->add('update_key', TextType::class, [
                'label' => 'grid.content.label.update_key',
                'required' => false,
                'attr' => [
                    'placeholder' => 'grid.content.placeholder.update_key'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'grid.content.submitbutton',
            ])
        ;
    }
}
