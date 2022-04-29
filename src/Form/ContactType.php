<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;

use function PHPSTORM_META\map;

class ContactType extends AbstractType
{
    private $translatorInterface;
    public function __construct(TranslatorInterface $translatorInterface)
    {
        $this->translatorInterface = $translatorInterface;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,[
                'attr'=>[
                    'placeholder'=>'Votre nom (obligatoire)',
                    'value'=>'Prenom nom'
                ],
                'required'=>true,
                'constraints' => [
                new NotBlank(),
                new Length(['min' => 3]),
            ],
            ])
            ->add('email',EmailType::class,[
                'label'=>$this->translatorInterface->trans('Email'),
                'attr'=>[
                    'value'=>'moncontact@mail.com',
                    'placeholder'=>$this->translatorInterface->trans('Your email (required)'),
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3]),
                ],
                'required'=>true,
            ])
            ->add('phone_number',TextType::class,[
                'label'=>'Téléphone',
                'attr'=>[
                    'placeholder'=>'Numéro de portable (obligatoire)',
                    'value'=>'781278288'
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3]),
                ],
                'required'=>true,
            ])
            ->add('message',TextareaType::class,[
                'label'=>'Message',
                'attr'=>[
                    'placeholder'=>'Votre message  (obligatoire)',
                    'cols'=>"10",
                    'rows'=>'5',
                    'value'=>'Message du formulaire de contact'
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3]),
                ],
                'required'=>true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
