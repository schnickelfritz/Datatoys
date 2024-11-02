<?php

declare(strict_types=1);

namespace App\Form\Grid;

use App\Enum\GridStatusEnum;
use App\Repository\GridscopeRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class GridtableFormType extends AbstractType
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
            ->add('name', TextType::class, [
                'label' => 'grid.table.label.name',
                'attr' => [
                    'focus' => true,
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Name must not be empty!']),
                ],
            ])
            ->add('scope', ChoiceType::class, [
                'label' => 'grid.table.label.scope',
                'choices' => $scopesChoices,
                'empty_data' => $firstScopeChoice,
                'multiple' => false,
                'expanded' => true,
                'required' => false,
                'placeholder' => false,
                'attr' => [
                ],
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'grid.table.label.status',
                'choices' => GridStatusEnum::all(),
                'multiple' => false,
                'expanded' => true,
                'required' => false,
            ])
            ->add('category', TextType::class, [
                'label' => 'grid.table.label.category',
                'required' => false,
                'attr' => ['placeholder' => 'optional'],
            ])
            ->add('numberOfSources', IntegerType::class, [
                'label' => 'grid.table.label.sources',
                'required' => false,
                'attr' => ['placeholder' => 'optional'],
            ])
            ->add('additionalExpense', IntegerType::class, [
                'label' => 'grid.table.label.expense',
                'required' => false,
                'attr' => ['placeholder' => 'optional'],
            ])
            ->add('notes', TextareaType::class, [
                'label' => 'grid.table.label.notes',
                'required' => false,
                'attr' => ['placeholder' => 'optional'],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'grid.table.submitbutton',
            ])
        ;
    }
}
