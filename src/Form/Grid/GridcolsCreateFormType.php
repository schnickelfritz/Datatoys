<?php

declare(strict_types=1);

namespace App\Form\Grid;

use App\Repository\GridscopeRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class GridcolsCreateFormType extends AbstractType
{
    public function __construct(
        private readonly GridscopeRepository $scopeRepository,
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $scopesChoices = $this->scopeRepository->choices();
        $firstScopeChoice = current($scopesChoices);

        $builder
            ->add('names', TextareaType::class, [
                'label' => 'grid.col.label.names',
                'attr' => [
                    'focus' => true,
                ],
            ])
            ->add('linkColsToScope', CheckboxType::class, [
                'label' => 'grid.col.label.link_to_scope',
                'required' => false,
            ])
            ->add('scope', ChoiceType::class, [
                'label' => 'grid.table.label.scope',
                'choices' => $scopesChoices,
                'data' => $firstScopeChoice,
                'multiple' => false,
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
}
