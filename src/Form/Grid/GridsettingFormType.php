<?php

declare(strict_types=1);

namespace App\Form\Grid;

use App\Repository\GridcolRepository;
use App\Repository\GridscopeRepository;
use App\Repository\GridsettingTypeRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class GridsettingFormType extends AbstractType
{
    public function __construct(
        private readonly GridsettingTypeRepository $gridsettingTypeRepository,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, [
                'label' => 'grid.setting.label.type',
                'choices' => $this->gridsettingTypeRepository->choices(),
                'multiple' => false,
                'expanded' => true,
                'required' => true,
                'attr' => [
                ],
            ])
            ->add('parameter', TextType::class, [
                'label' => 'grid.setting.label.parameter',
                'required' => false,
                'attr' => [
                    'focus' => true,
                ],
            ])
            ->add('columns', ChoiceType::class, [
                'label' => 'grid.setting.label.columns',
                'choices' => $options['columns'],
                'data' => $options['selected_columns'],
                'multiple' => true,
                'expanded' => true,
                'required' => true,
                'attr' => [
                ],
            ])
            ->add('override_param', CheckboxType::class, [
                'label' => 'grid.setting.label.override_param',
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'grid.setting.create.submitbutton',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'selected_columns' => null,
            'columns' => null,
        ]);
    }

}
