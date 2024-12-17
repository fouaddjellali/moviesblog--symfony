<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ResetPasswordRequestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => [
                    'autocomplete' => 'email', 
                    'class' => 'w-full px-8 py-4 rounded-lg font-medium bg-gray-100 border border-gray-200 text-sm placeholder-gray-500 focus:outline-none focus:border-gray-400 focus:bg-white'
            ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter your email',
                    ]),
                ],
            ])->add('submit', SubmitType::class, [
                'label' => 'Envoyer l\'email de réinitialisation',
                'attr' => [
                    'class' => 'w-full bg-red-600 py-3 text-white rounded-lg hover:opacity-90 transition-all duration-300'
                ],
            ])
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
