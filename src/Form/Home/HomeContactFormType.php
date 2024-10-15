<?php

namespace App\Form\Home;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class HomeContactFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $choices = [
            'contact.abouts.contact' => 'Contact',
            'contact.abouts.help' => 'Help',
            'contact.abouts.praise' => 'Praise',
            'contact.abouts.improvement_suggestion' => 'Improvement_Suggestion',
            'contact.abouts.error_found' => 'Error_Found',
            'contact.abouts.greeting' => 'Greeting',
        ];
        $firstChoice = current($choices);

        $builder
            ->add('message', TextareaType::class, [
                'label' => 'contact.label.message',
                'attr' => [
                    'focus' => true,
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Your Message must not be empty!']),
                ]
            ])
            ->add('contactEmail', EmailType::class, [
                'label' => 'contact.label.email',
                'required' => false,
                'attr' => [
                    'placeholder' => 'optional',
                ]
            ])
            ->add('about', ChoiceType::class, [
                'label' => 'contact.label.about',
                'choices' => $choices,
                'multiple' => false,
                'expanded' => true,
                'required' => false,
                'placeholder' => false,
                'data' => $firstChoice,
                'attr' => [
                ],
            ])
            ->add('sendcopy', CheckboxType::class, [
                'label' => 'contact.label.copy',
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'contact.submitbutton',
            ])
            ;
    }
}