<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Vich\UploaderBundle\Form\Type\VichFileType;

class UserImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('imageFile', VichFileType::class, [
                'label' => false,
                'required'     => false,
                'allow_delete' => true,
                'download_uri' => false,
                'help' => 'Lade ein Profilbild hoch, um dich besser vernetzen zu können.',
                'constraints' => [
                    new Image([
                        'minWidth' => 600, // Mindestbreite in Pixeln
                        'minHeight' => 600, // Mindesthöhe in Pixeln
                        'mimeTypes' => ['image/jpeg', 'image/png'], // Erlaubte Dateitypen
                        'mimeTypesMessage' => 'Please upload a valid JPEG or PNG image.',
                        'minWidthMessage' => 'The image width must be at least {{ min_width }} pixels.',
                        'minHeightMessage' => 'The image height must be at least {{ min_height }} pixels.',
                    ]),
                ],
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
