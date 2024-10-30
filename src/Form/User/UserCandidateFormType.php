<?php

declare(strict_types=1);

namespace App\Form\User;

use App\Enum\RoleEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserCandidateFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'admin.candidate.label.name',
                'attr' => [
                    'focus' => true,
                ],
                'constraints' => [
                    new NotBlank(['message' => 'The Name must not be empty!']),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'admin.candidate.label.email',
                'constraints' => [
                    new NotBlank(['message' => 'The Name must not be empty!']),
                ],
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'admin.candidate.label.roles',
                'choices' => RoleEnum::all(),
                'multiple' => true,
                'expanded' => true,
                'required' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'admin.candidate.create.submitbutton',
            ])
        ;
    }
}
