<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenom',TextType::class,[
                'label' => 'Prénom *',
                'attr' => [
                    'placeholder' => "prénom",
                ]  
            ])
            ->add('nom',TextType::class,[
                'label' => 'Nom *',
            'attr' => [
                'placeholder' => "Nom",
            ]
            ])
            ->add('email',EmailType::class)
            ->add('adresse',AdresseType::class,[
                'label'=>false
            ])
            ->add('isVerified',CheckboxType::class)
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
