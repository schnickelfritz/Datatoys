<?php

declare(strict_types=1);

namespace App\Form\Grid;

use App\Repository\GridscopeRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class GridcolUpdateFormType extends AbstractType
{
    public function __construct(
        private readonly GridscopeRepository $scopeRepository,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $scopesChoices = $this->scopeRepository->choices();

        $builder
            ->add('name', TextareaType::class, [
                'label' => 'grid.col.label.name',
                'attr' => [
                    'focus' => true,
                ],
            ])
            ->add('scopes', ChoiceType::class, [
                'label' => 'grid.table.label.scope',
                'choices' => $scopesChoices,
                'data' => $options['selected_scopes'],
                'multiple' => true,
                'mapped' => false,
                'expanded' => true,
                'required' => false,
                'placeholder' => false,
                'attr' => [
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'grid.col.create.submitbutton',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'selected_scopes' => null,
        ]);
    }
}
