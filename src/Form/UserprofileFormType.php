<?php

namespace App\Form;

use App\Entity\Hobby;
use App\Entity\Interest;
use App\Entity\University;
use App\Entity\UserProfile;
use App\Enums\PerformanceCourseEnum;
use App\Repository\HobbyRepository;
use App\Repository\InterestRepository;
use App\Repository\UniversityRepository;
use App\Transformer\CourseTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserprofileFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('inYear',NumberType::class,[
                'row_attr' => ['class' => 'form-floating mb-3'],
                'required' => true,
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('outYear',NumberType::class,[
                'row_attr' => ['class' => 'form-floating mb-3'],
                'required' => true,
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('examType',ChoiceType::class, [
                'row_attr' => ['class' => 'slim-form mb-3'],
                'attr' => ['class' => 'slim-select-single-exam-type'],
                'choices' => ['abitur' => 'abitur', 'fachabitur' => 'fachabitur', 'msa' => 'msa'],
                'required' => true,
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('displayName', ChoiceType::class, [
                'row_attr' => ['class' => 'slim-form mb-3'],
                'attr' => ['class' => 'slim-select-single-display-name'],
                'choices' => ['fullnameEx' => 'fullnameEx', 'firstnameEx' => 'firstnameEx', 'lastnameEx' => 'lastnameEx'],
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('favoriteSchoolSubjects', EnumType::class, [
                'row_attr' => ['class' => 'slim-form mb-3'],
                'attr' => ['class' => 'slim-select-multi-favorite-school-subjects'],
                'class' => PerformanceCourseEnum::class,
                'choices' => PerformanceCourseEnum::cases(),
                'multiple' => true,
                'expanded' => false,
            ])
            ->add('performanceCourse', EnumType::class, [
                'row_attr' => ['class' => 'slim-form mb-3'],
                'attr' => ['class' => 'slim-select-multi-performance-course', 'data-max' => 2],
                'class' => PerformanceCourseEnum::class,
                'choices' => PerformanceCourseEnum::cases(),
                'multiple' => true,
                'expanded' => false,
            ])
            ->add('userHobbies', EntityType::class, [
                'row_attr' => ['class' => 'slim-form'],
                'attr' => ['class' => 'slim-select-multi-exam-type'],
                'query_builder' => function (HobbyRepository $er) {
                    return $er->createQueryBuilder('e')
                        ->leftJoin('e.category', 'c')
                        ->orderBy('c.label', 'ASC')
                        ->addOrderBy('e.label', 'ASC');
                },
                'class' => Hobby::class,
                'multiple' => true,
                'expanded' => false,
                'group_by' => function($choice) {
                    return $choice->getCategory();
                },
            ])
            ->add('about', TextareaType::class, [
                'row_attr' => ['class' => 'form-floating mb-3'],
                'attr' => ['style' => 'min-height: 200px'],
            ])
            ->add('portfolioLink', TextType::class, [
                'row_attr' => ['class' => 'form-floating mb-3'],
            ])
            ->add('studium', TextType::class, [
                'row_attr' => ['class' => 'form-floating mb-3'],
            ])
            ->add('userUniversity', EntityType::class, [
                'class' => University::class,
                'row_attr' => ['class' => 'slim-form mb-3'],
                'attr' => ['class' => 'slim-select-multi-university', 'data-max' => 1],
                'query_builder' => function (UniversityRepository $er) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.county', 'ASC')
                        ->addOrderBy('e.city', 'ASC')
                        ->addOrderBy('e.shortTitle', 'ASC');
                },
                'placeholder' => "auswählen",
                'empty_data' => null,
                'multiple' => false,
                'expanded' => false,
                'group_by' => function($choice) {
                    return $choice->getCounty();
                },
            ])
            ->add('currentProfession', TextType::class, [
                'row_attr' => ['class' => 'form-floating mb-3'],
            ])
            ->add('currentCompany', TextType::class, [
                'row_attr' => ['class' => 'form-floating mb-3'],
            ])
            ->add('networkState', ChoiceType::class, [
                'row_attr' => ['class' => 'slim-form mb-3'],
                'attr' => ['class' => 'slim-select-single-network-state'],
                'choices' => ['public' => 'public', 'registered' => 'registered', 'private' => 'private'],
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('userInterests', EntityType::class, [
                'row_attr' => ['class' => 'slim-form mb-3'],
                'attr' => ['class' => 'slim-select-multi-interests'],
                'query_builder' => function (InterestRepository $er) {
                    return $er->createQueryBuilder('e')
                        ->leftJoin('e.category', 'c')
                        ->orderBy('c.label', 'ASC')
                        ->addOrderBy('e.label', 'ASC');
                },
                'class' => Interest::class,
                'multiple' => true,
                'expanded' => false,
                'group_by' => function($choice) {
                    return $choice->getCategory();
                },
            ])
        ;

        $builder->get('favoriteSchoolSubjects')->addModelTransformer(new CourseTransformer());
        $builder->get('performanceCourse')->addModelTransformer(new CourseTransformer());

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserProfile::class,
        ]);
    }
}
