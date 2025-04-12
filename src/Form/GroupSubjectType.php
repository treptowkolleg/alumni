<?php

namespace App\Form;

use App\Entity\GroupSubject;
use App\Entity\Hobby;
use App\Entity\Interest;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupSubjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class,[
                'row_attr' => ['class' => 'form-floating mb-3'],
                'attr' => ['placeholder' => 'title'],
            ])
            ->add('description',TextType::class,[
                'row_attr' => ['class' => 'form-floating mb-3'],
                'attr' => ['placeholder' => 'description'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GroupSubject::class,
        ]);
    }
}
