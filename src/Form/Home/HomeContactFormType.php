<?php

namespace App\Form\Home;

use Symfony\Component\Form\AbstractType;
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
            'Help' => 'help',
            'Contact' => 'contact',
            'Praise' => 'praise',
            'Suggestion for Improvement' => 'improve',
            'Found an Error' => 'error_found',
            'Greeting' => 'greeting',
            'Misc' => 'misc',
        ];
        $firstChoice = end($choices);

        $builder
            ->add('message', TextareaType::class, [
                'label' => 'Your Message',
                'constraints' => [
                    new NotBlank(['message' => 'Your Message must not be empty!']),
                ]
            ])
            ->add('contactEmail', EmailType::class, [
                'label' => 'Your Email Address',
                'required' => false,
            ])
            ->add('about', ChoiceType::class, [
                'label' => 'About',
                'choices' => $choices,
                'multiple' => false,
                'expanded' => true,
                'required' => false,
                'placeholder' => false,
                'data' => $firstChoice,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Send Message',
            ])
            ;
    }
}