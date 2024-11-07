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
    ) {
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
                    'placeholder' => 'grid.col.placeholder.names',
                ],
            ])
            ->add('scopes', ChoiceType::class, [
                'required' => false,
                'label' => 'grid.col.label.scopes',
                'choices' => $scopesChoices,
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'grid.col.create.submitbutton',
            ])
        ;
    }
}
