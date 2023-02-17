<?php

namespace App\Form;

use App\Entity\Message;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options;
        $builder
        ->add('recepteur',EntityType::class,[
            'class'=>User::class,
            'attr'=>[
                'class'=>'select2'
            ],
            'query_builder' => function (UserRepository $er) {
                return $er->createQueryBuilder('u')
                    ->where("u.id <> 1");
                    // ->orderBy('u.username', 'ASC');
            },
            'choice_label'=>function($user){
                return $user->getFullName();
            }
            ])
        ->add('message')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Message::class
        ]);
    }
}
