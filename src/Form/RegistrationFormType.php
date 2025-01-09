<?php

namespace App\Form;

use App\Entity\School;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'row_attr' => ['class' => 'form-floating mb-3'],
                'attr' => ['placeholder' => 'Firstname'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter your firstname',
                    ]),
                ],
            ])
            ->add('lastname', TextType::class, [
                'row_attr' => ['class' => 'form-floating mb-5'],
                'attr' => ['placeholder' => 'Lastname'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter your lastname',
                    ]),
                ],
            ])
            ->add('hasSchool', CheckboxType::class, [
                'row_attr' => ['class' => 'mb-3'],
            ])
            ->add('school', EntityType::class, [
                'class' => School::class,
                'row_attr' => ['class' => 'form-floating mb-3'],
                'attr' => ['placeholder' => 'School'],
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.city', 'ASC')
                        ->addOrderBy('u.title', 'ASC');
                },
                'group_by' => function (School $school) {
                    return $school->getCity();
                },
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter your school',
                    ]),
                ],
            ])
            ->add('userType', ChoiceType::class, [
                'choices' => ['student' => 'Student', 'teacher' => 'Teacher','employer' => 'Employer'],
                'row_attr' => ['class' => 'form-floating mb-3'],
                'attr' => ['placeholder' => 'User type'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter your user type',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'row_attr' => ['class' => 'form-floating mb-3'],
                'attr' => ['placeholder' => 'Email'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter your email address',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'row_attr' => ['class' => 'form-floating mb-3'],
                'attr' => ['autocomplete' => 'new-password', 'placeholder' => 'Password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
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
