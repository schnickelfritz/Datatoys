<?php

declare(strict_types=1);

namespace App\Form\Grid;

use App\Repository\GridscopeRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class GridpoolFormType extends AbstractType
{
    public function __construct(
        private GridscopeRepository $scopeRepository,
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $choices = $this->scopeRepository->choices();
        $firstChoice = current($choices);

        $builder
            ->add('name', TextType::class, [
                'label' => 'grid.pool.label.name',
                'attr' => [
                    'focus' => true,
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Your Message must not be empty!']),
                ],
            ])
            ->add('scope', ChoiceType::class, [
                'label' => 'grid.pool.label.scope',
                'choices' => $choices,
                'data' => $firstChoice,
                'multiple' => false,
                'expanded' => true,
                'required' => false,
                'placeholder' => false,
                'attr' => [
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'grid.pool.submitbutton',
            ])
        ;
    }
}
