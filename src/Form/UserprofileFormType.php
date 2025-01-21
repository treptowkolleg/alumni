<?php

namespace App\Form;

use App\Entity\UserProfile;
use App\Enums\PerformanceCourseEnum;
use App\Transformer\CourseTransformer;
use App\Transformer\TagTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class UserprofileFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $interests = [
            'Sprachen und Literatur' => [
                'Lesen' => 'reading',
                'Kreatives Schreiben' => 'creative_writing',
                'Debattieren' => 'debating',
                'Fremdsprachen' => 'foreign_languages',
                'Literaturanalyse' => 'literature_analysis',
            ],
            'Naturwissenschaften und Technik' => [
                'Physik' => 'physics',
                'Chemie' => 'chemistry',
                'Biologie' => 'biology',
                'Astronomie' => 'astronomy',
                'Robotik' => 'robotics',
                'Programmieren' => 'coding',
                'Umweltforschung' => 'environmental_science',
            ],
            'Mathematik und Logik' => [
                'Mathematik-Wettbewerbe' => 'math_competitions',
                'Statistik' => 'statistics',
                'Logikspiele' => 'logic_games',
                'Finanzbildung' => 'financial_literacy',
                'Geometrie' => 'geometry',
            ],
            'Kunst und Design' => [
                'Malen und Zeichnen' => 'painting_drawing',
                'Fotografie' => 'photography',
                'Grafikdesign' => 'graphic_design',
                'Skulptur' => 'sculpture',
                'Mode-Design' => 'fashion_design',
            ],
            'Musik und darstellende Kuenste' => [
                'Musikinstrumente spielen' => 'playing_instruments',
                'Gesang' => 'singing',
                'Tanzen' => 'dancing',
                'Theater und Schauspiel' => 'theater_acting',
                'Chor' => 'choir',
                'Musikproduktion' => 'music_production',
            ],
            'Gesellschaft und Kultur' => [
                'Geschichte' => 'history',
                'Philosophie' => 'philosophy',
                'Sozialwissenschaften' => 'social_sciences',
                'Politik und Debatten' => 'politics_debating',
                'Kulturelle Studien' => 'cultural_studies',
            ],
            'Sport und Gesundheit' => [
                'Teamsportarten' => 'team_sports',
                'Einzelsportarten' => 'individual_sports',
                'Fitness' => 'fitness',
                'Yoga und Meditation' => 'yoga_meditation',
                'Ernaehrungswissenschaft' => 'nutrition',
            ],
            'Medien und Kommunikation' => [
                'Film und Videoproduktion' => 'film_video_production',
                'Journalismus' => 'journalism',
                'Podcasting' => 'podcasting',
                'Social Media Management' => 'social_media_management',
                'Fotobearbeitung' => 'photo_editing',
            ],
            'Freizeit und Outdoor' => [
                'Wandern' => 'hiking',
                'Camping' => 'camping',
                'Gaertnern' => 'gardening',
                'Vogelbeobachtung' => 'birdwatching',
                'Reisen' => 'traveling',
                'Angeln' => 'fishing',
            ],
            'Praktische Fertigkeiten und Handwerk' => [
                'Kochen und Backen' => 'cooking_baking',
                'Schreinern und Bauen' => 'woodworking',
                'Elektronikbasteln' => 'electronics',
                'Naehen und Textilarbeiten' => 'sewing_textiles',
                'DIY-Projekte' => 'diy_projects',
            ],
        ];

        $builder
            ->add('inYear',NumberType::class,[
                'row_attr' => ['class' => 'form-floating mb-3'],
            ])
            ->add('outYear',NumberType::class,[
                'row_attr' => ['class' => 'form-floating mb-3'],
            ])
            ->add('examType',ChoiceType::class, [
                'row_attr' => ['class' => 'slim-form mb-3'],
                'attr' => ['class' => 'slim-select-single-exam-type'],
                'choices' => ['abitur' => 'abitur', 'fachabitur' => 'fachabitur', 'msa' => 'msa'],
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
            ->add('hobbies', ChoiceType::class, [
                'row_attr' => ['class' => 'slim-form'],
                'attr' => ['class' => 'slim-select-multi-exam-type'],
                'choices' => $interests,
                'multiple' => true,  // Mehrfachauswahl moeglich
                'expanded' => false,  // Checkboxen statt Dropdown
                'group_by' => function ($choice, $key, $value) use ($interests) {
                    // Gruppierung nach Hauptkategorien
                    foreach ($interests as $group => $items) {
                        if (in_array($value, $items, true)) {
                            return $group; // Rueckgabe der Gruppe
                        }
                    }
                    return 'Andere';
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
            ->add('university', TextType::class, [
                'row_attr' => ['class' => 'form-floating mb-3'],
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
            ->add('interests', ChoiceType::class, [
                'row_attr' => ['class' => 'slim-form mb-3'],
                'attr' => ['class' => 'slim-select-multi-favorite-school-subjects'],
                'choices' => [
                    'Berufsbereiche' => [
                        'IT und Softwareentwicklung' => 'it_softwareentwicklung',
                        'Marketing und Werbung' => 'marketing_werbung',
                        'Vertrieb und Verkauf' => 'vertrieb_verkauf',
                        'Personal und HR' => 'personal_hr',
                        'Finanzen und Buchhaltung' => 'finanzen_buchhaltung',
                        'Beratung und Consulting' => 'beratung_consulting',
                        'Gesundheitswesen und Pflege' => 'gesundheitswesen_pflege',
                        'Recht und Verwaltung' => 'recht_verwaltung',
                    ],
                    'Berufliche Weiterentwicklung' => [
                        'Fuehrungskompetenzen' => 'fuehrungskompetenzen',
                        'Projektmanagement' => 'projektmanagement',
                        'Entrepreneurship' => 'entrepreneurship',
                        'Weiterbildung und Training' => 'weiterbildung_training',
                        'Karriereplanung und Coaching' => 'karriereplanung_coaching',
                        'Kommunikationsfaehigkeiten' => 'kommunikationsfaehigkeiten',
                    ],
                    'Berufliche Taetigkeiten' => [
                        'Teamarbeit' => 'teamarbeit',
                        'Selbststaendigkeit' => 'selbststaendigkeit',
                        'Verhandlungen und Vertragsabschluesse' => 'verhandlungen_vertragsabschluesse',
                        'Rekrutierung und Mitarbeiterfuehrung' => 'rekrutierung_mitarbeiterfuehrung',
                        'Strategische Planung' => 'strategische_planung',
                        'Kundenbetreuung und -beratung' => 'kundenbetreuung_beratung',
                        'Datenanalyse und Forschung' => 'datenanalyse_forschung',
                        'Marktforschung und Analyse' => 'marktforschung_analyse',
                    ],
                ],
                'expanded' => false, // optional, wenn du Checkboxen oder Radios moechtest
                'multiple' => true, // optional, wenn du mehrere Optionen auswaehlen lassen moechtest
                'label' => 'Berufliche Interessen',
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
