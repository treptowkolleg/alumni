<?php

namespace App\Form;

use App\Entity\Chat;
use App\Entity\ChatMessage;
use App\Entity\DirectMessage;
use App\Entity\User;
use App\Entity\UserProfile;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChatMessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $me = $options['me'];

        $builder
            ->add('recipient', EntityType::class, [
                'choice_label' => fn(User $user) => $user->getUserProfiles()->first(),
                'class' => User::class,
                'attr' => ['class' => 'slim-select-multi-recipient', 'data-max' => 1],
                'empty_data' => null,
                'placeholder' => 'auswählen',
                'required' => true,
                'query_builder' => function (UserRepository $repo) use ($me) {
                    return $repo->createVisibleRecipientsQuery($me);
                },
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('title', TextType::class,[
                'label' => "Subject",
                'row_attr' => ['class' => 'form-floating mb-3'],
                'attr' => ['placeholder' => 'Subject'],
                'required' => true,
            ])
            ->add('content', TextareaType::class,[
                'label' => "Message",
                'row_attr' => ['class' => 'form-floating mb-3'],
                'required' => true,
                'attr' => ['style' => 'min-height:250px','placeholder' => 'Message'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DirectMessage::class,
            'me' => null,
        ]);
    }
}
