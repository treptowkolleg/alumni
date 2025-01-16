<?php

namespace App\Form;

use App\Entity\Chat;
use App\Entity\ChatMessage;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChatMessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('subject', TextType::class,[
                'row_attr' => ['class' => 'form-floating mb-3'],
                'attr' => ['placeholder' => 'Subject'],
                'required' => true,
            ])
            ->add('message', TextareaType::class,[
                'row_attr' => ['class' => 'form-floating mb-3'],
                'required' => true,
                'attr' => ['style' => 'min-height:250px','placeholder' => 'Message'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ChatMessage::class,
        ]);
    }
}
