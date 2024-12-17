<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ResetPasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Nouveau mot de passe',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un nouveau mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères.',
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('confirmPassword', PasswordType::class, [
                'label' => 'Confirmation du nouveau mot de passe',
                'mapped' => false,
                'required' => true,
            ])->add('save', SubmitType::class, [
                'label' => 'Modifier le mot de passe',
                'attr' => [
                    'class' => 'w-full bg-red-600 py-3 text-white rounded-lg hover:opacity-90 transition-all duration-300',
                ],
            ]);
            
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}
