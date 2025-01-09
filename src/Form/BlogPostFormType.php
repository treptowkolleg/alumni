<?php

namespace App\Form;

use App\Entity\BlogPost;
use App\Entity\BlogType;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichFileType;

class BlogPostFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'row_attr' => ['class' => 'form-floating mb-3'],
                'attr' => ['placeholder' => 'Title'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a title',
                    ]),
                ],
            ])
            ->add('subtitle', TextType::class, [
                'row_attr' => ['class' => 'form-floating mb-3'],
                'attr' => ['placeholder' => 'Subtitle'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a subtitle',
                    ]),
                ],
            ])
            ->add('type', EntityType::class, [
                'class' => BlogType::class,
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.title', 'ASC');
                },
                'row_attr' => ['class' => 'form-floating mb-3'],
                'attr' => ['placeholder' => 'Type'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please select an blogpost type',
                    ]),
                ],
            ])
            ->add('content', TextType::class, [
                'row_attr' => ['class' => 'form-floating mb-3'],
                'attr' => ['placeholder' => 'Description','hidden' => 'hidden'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter some content',
                    ]),
                ],
            ])
            ->add('imageFile', VichFileType::class, [
                'required'     => false,
                'allow_delete' => true,
                'download_uri' => true,
                'help' => 'Ein Artikelbild sollte in jedem Fall verwendet werden, insbesondere aber dann, wenn es sich um einen Leitartikel handelt.',
            ])
            ->add('audioFile', VichFileType::class, [
                'required'     => false,
                'allow_delete' => true,
                'download_uri' => true,
                'help' => 'Für Podcasts benötigt, um Audio-Datei bereitzustellen.',
                'constraints' => [
                    new File([
                        'maxSize' => '4096k',
                        'mimeTypes' => [
                            'audio/mpeg',
                            'audio/mp3',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid mp3 file',
                    ])
                ]
            ])
            ->add('isPublished', CheckboxType::class, [
                'label' => 'Publish',
                'row_attr' => ['class' => 'mb-4'],
            ])
            ->add('isLeadPost', CheckboxType::class, [
                'label' => 'isLeadPost',
                'help' => 'Aktivieren, wenn dieser Artikel als Leitartikel auf der Startseite erscheinen soll.'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BlogPost::class,
        ]);
    }
}
