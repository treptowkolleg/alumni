<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\EventType;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DomCrawler\Field\TextareaFormField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class EventFormType extends AbstractType
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
            ->add('type', EntityType::class, [
                'class' => EventType::class,
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.title', 'ASC');
                },
                'row_attr' => ['class' => 'form-floating mb-3'],
                'attr' => ['placeholder' => 'Type'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please select an event type',
                    ]),
                ],
            ])
            ->add('description', TextType::class, [
                'row_attr' => ['class' => 'form-floating mb-3'],
                'attr' => ['placeholder' => 'Description','hidden' => 'hidden'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a description',
                    ]),
                ],
            ])
            ->add('location', TextType::class, [
                'row_attr' => ['class' => 'form-floating mb-5'],
                'attr' => ['placeholder' => 'Location'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a location',
                    ]),
                ],
            ])
            ->add('startDate', DateTimeType::class, [
                'row_attr' => ['class' => 'form-floating'],
                'attr' => ['placeholder' => 'Start date'],
                'widget' => 'single_text',
                'constraints' => [
                    new NotNull([
                        'message' => 'Please enter a start date',
                    ]),
                ],
            ])
            ->add('endDate', DateTimeType::class, [
                'row_attr' => ['class' => 'form-floating'],
                'attr' => ['placeholder' => 'End date'],
                'widget' => 'single_text',
                'constraints' => [
                    new NotNull([
                        'message' => 'Please enter an end date',
                    ]),
                ],
            ])
            ->add('website', TextType::class, [
                'row_attr' => ['class' => 'form-floating mb-3'],
                'attr' => ['placeholder' => 'Website'],
                'required' => false,
            ])
            ->add('contactPerson', TextType::class, [
                'row_attr' => ['class' => 'form-floating mb-3'],
                'attr' => ['placeholder' => 'Contact person'],
                'required' => false,
            ])
            ->add('contactEmail', TextType::class, [
                'row_attr' => ['class' => 'form-floating mb-3'],
                'attr' => ['placeholder' => 'Contact email'],
                'required' => false,
            ])
        ;

        $builder->get('startDate')->addModelTransformer(new CallbackTransformer(
            function ($value) {
                if(!$value) {
                    $value =  new \DateTime('now +1 month');
                    $value->setTime(10, 0);
                }
                return $value;
            },
            function ($value) {
                return $value;
            }
        ));

        $builder->get('startDate')->addModelTransformer(new CallbackTransformer(
            function ($value) {
                if(!$value) {
                    $value =  new \DateTime('now');
                    $value->setTime(10, 0);
                }
                return $value;
            },
            function ($value) {
                return $value;
            }
        ));

        $builder->get('endDate')->addModelTransformer(new CallbackTransformer(
            function ($value) {
                if(!$value) {
                    $value =  new \DateTime('now');
                    $value->setTime(10, 0);
                }
                return $value;
            },
            function ($value) {
                return $value;
            }
        ));

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
