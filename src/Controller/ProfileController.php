<?php

namespace App\Controller;

use App\Entity\GroupSubject;
use App\Entity\Newsletter;
use App\Entity\PersonOffer;
use App\Entity\User;
use App\Entity\UserProfile;
use App\Enums\MessageVisibilityScope;
use App\Enums\PerformanceCourseEnum;
use App\Form\NewsLetterToggleFormType;
use App\Form\UserImageType;
use App\Form\UserprofileFormType;
use App\Operator\SoundExpression;
use App\Repository\ChatRepository;
use App\Repository\GroupSubjectRepository;
use App\Repository\NewsletterRepository;
use App\Repository\PersonOfferRepository;
use App\Repository\SchoolRepository;
use App\Repository\SubjectPostRepository;
use App\Repository\UserProfileRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

#[Route('/profile', name: 'profile_')]
class ProfileController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(Request $request, UserProfileRepository $userProfileRepository, ChatRepository $chatRepository, NewsletterRepository $newsletterRepository, SubjectPostRepository $subjectPostRepository, PersonOfferRepository $offerRepository, EntityManagerInterface $entityManager): Response
    {
        $userProfile = $userProfileRepository->findOneBy(['user' => $this->getUser()]);
        $newsletter = $newsletterRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $chats = $chatRepository->findAllChats($this->getUser());

        $posts = $subjectPostRepository->findBy(['owner' => $this->getUser()]);

        $groups = [];
        foreach ($posts as $post)
        {
            $groups[$post->getSubject()->getId()] = $post->getSubject();
        }

        $offers = [];

        if($userProfile) {
            $user = $userProfile->getUser();
            $offers = $offerRepository->findBy([
                'city' => $user->getSchool()->getCity(),
                'active' => true
            ]);
        }


        $form = $this->createForm(NewsLetterToggleFormType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            if($newsletter) {
                $entityManager->remove($newsletter);
                $this->addFlash('success','Newsletter erfolgreich abbestellt.');
            } else {
                $newsletter = new Newsletter();
                $newsletter->setEmail($this->getUser()->getUserIdentifier());
                $entityManager->persist($newsletter);
                $this->addFlash('success','Newsletter erfolgreich abonniert.');
            }
            $entityManager->flush();
            return $this->redirectToRoute('profile_index');
        }

        return $this->render('profile/index.html.twig', [
            'form' => $form,
            'controller_name' => 'ProfileController',
            'user_profile' => $userProfile,
            'has_newsletter' => $newsletter,
            'chats' => $chats,
            'offers' => $offers,
            'groups' => $groups,
        ]);
    }

    #[Route('/update', name: 'profile_new', methods: ['GET', 'POST'])]
    public function profileNew(Request $request, UserProfileRepository $profileRepository, EntityManagerInterface $entityManager): Response
    {
        $profile = $profileRepository->findOneBy(['user' => $this->getUser()]);
        if(!$profile) {
            $profile = new UserProfile();
            $profile->setUser($this->getUser());
        }
        $form = $this->createForm(UserprofileFormType::class, $profile);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($profile);
            $entityManager->flush();
            $this->addFlash('success', 'Dein Alumni-Profil wurde erfolgreich gespeichert.');
            return $this->redirectToRoute('profile_index');
        }
        return $this->render('profile/profile_new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/image/update', name: 'image_new', methods: ['GET', 'POST'])]
    public function imageUpload(Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $userRepository->find($this->getUser());
        $image = $user;
        $form = $this->createForm(UserImageType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Dein Profilbild wurde erfolgreich aktualisiert.');
            return $this->redirectToRoute('profile_image_new');
        }
        return $this->render('profile/image_update.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/settings', name: 'settings', methods: ['GET', 'POST'])]
    public function settings(Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $userRepository->find($this->getUser());

        $cookies = $request->cookies->all();

        return $this->render('profile/settings.html.twig', [
            'cookies' => $cookies,
        ]);
    }

    #[Route('/settings/{section}', name: 'settings_do', methods: ['GET', 'POST'])]
    public function PersonalSettings(Request $request, UserRepository $userRepository, NewsletterRepository $newsletterRepository, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager, string $section): Response
    {
        $subtitle = 'Einstellungen';
        $user = $userRepository->find($this->getUser());

        $form = $this->createFormBuilder($user);

        switch ($section) {
            case 'person':
                $form->add('firstname',TextType::class,[
                    'required'=>true,'row_attr' => ['class' => 'form-floating mb-3'],
                    'attr' => ['placeholder' => 'Firstname'],
                ])
                    ->add('lastname',TextType::class,[
                        'required'=>true,'row_attr' => ['class' => 'form-floating mb-3'],
                        'attr' => ['placeholder' => 'Lastname'],
                    ]);
                $subtitle = "Personendaten";
                break;
            case 'email':
                $form->add('email',EmailType::class,[
                    'required'=>true,'row_attr' => ['class' => 'form-floating mb-3'],
                    'attr' => ['placeholder' => 'Email'],
                    'help' => 'Nach Änderung wirst du automatisch ausgeloggt. Du musst deine neue E-Mail-Adresse ebenfalls verifizieren.',
                ]);
                $subtitle = "E-Mail-Adresse";
                break;
            case 'password':
                $form->add('plainPassword',PasswordType::class,[
                    'required'=>true,'row_attr' => ['class' => 'form-floating mb-3'],
                    'attr' => ['placeholder' => 'Password'],
                    'mapped' => false,
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
                ]);
                $subtitle = "Passwort";
                break;
            case 'privacy':
                $form->add('isContactable',CheckboxType::class,[
                    'required' => false,
                    'label' => 'Nachrichtensystem aktivieren',
                    'help' => 'Deaktivieren, um keine Nachrichten von anderen erhalten zu können.',
                    ])
                    ->add('messageVisibilityScope', ChoiceType::class, [
                        'label' => 'Sichtbarkeit im Nachrichtensystem',
                        'choices' => [
                            MessageVisibilityScope::all->label() => MessageVisibilityScope::all,
                            MessageVisibilityScope::SameSchool->label() => MessageVisibilityScope::SameSchool,
                            MessageVisibilityScope::ContactsOnly->label() => MessageVisibilityScope::ContactsOnly,
                            MessageVisibilityScope::Hidden->label() => MessageVisibilityScope::Hidden,
                        ],
                        'choice_label' => fn($choice, $key, $value) => $choice->label(),
                        'choice_value' => fn(?MessageVisibilityScope $choice) => $choice?->value,
                        'required' => true,
                        'expanded' => true,
                    ])
                    ->add('notifyNewMail',CheckboxType::class,[
                        'required' => false,
                        'label' => 'Über neue Nachrichten informieren',
                        'help' => 'Üblicherweise wirst du einmal wöchentlich informiert.',
                    ])
                    ->add('hasPinnboard',CheckboxType::class,[
                        'required' => false,
                        'label' => 'Pinnwand aktivieren',
                        'help' => 'Wenn aktiv, richtet sich die Sichtbarkeit nach deinem Alumni-Profil.',
                    ])
                    ->add('isEventsVisible',CheckboxType::class,[
                        'required' => false,
                        'label' => 'Teilnahme an Veranstaltungen anzeigen',
                        'help' => 'Deaktivieren, um Teilnahmen zu verbergen.',
                    ])
                ;
                $subtitle = "Privatsphäre";
                break;
            case 'newsletter':
                $form->add('HasNewsletter',CheckboxType::class,[
                    'required' => false,
                    'label' => 'Newsletter erhalten',
                    'help' => 'Üblicherweise versenden wir Newsletter einmal im Quartal.',
                ]);
                $subtitle = "Newsletter";
                break;
        }




        $form->add('save', SubmitType::class, [
            'label' => 'Aktualisieren','attr' => ['class' => 'btn btn-primary w-100 mt-3 rounded-2 shadow-sm']
        ]);
        $form = $form->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($section == 'person') {
                $user->setFirstnameSoundEx(SoundExpression::generate($user->getFirstname()));
                $user->setLastnameSoundEx(SoundExpression::generate($user->getLastname()));
            }

            if($section == 'email' and $user->getEmail() != $this->getUser()->getUserIdentifier()) {
                $user->setVerified(false);
            }

            if($section == 'password') {
                $password = $passwordHasher->hashPassword($user, $form->get('plainPassword')->getData());
                $user->setPassword($password);
            }

            if($section == 'newsletter') {
                if($user->hasNewsletter()) {
                    if(!$newsletterRepository->findOneBy(['email' => $user->getEmail()])) {
                        $newsletter = new Newsletter();
                        $newsletter->setUser($user);
                        $newsletter->setEmail($user->getEmail());
                        $newsletter->addSchool($user->getSchool());
                        $entityManager->persist($newsletter);
                    }
                } else {
                    if($newsletter = $newsletterRepository->findOneBy(['email' => $user->getEmail()])) {
                        $entityManager->remove($newsletter);
                    }
                }
            }

            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success','Daten wurden aktualisiert.');
            return $this->redirectToRoute('profile_settings');
        }

        return $this->render('profile/settings_form.html.twig', [
            'meta_title' => 'Konto',
            'meta_subtitle' => $subtitle,
            'sidebar' => 'profile-index',
            'form' => $form,
        ]);
    }

    #[Route('/schule/{page}', name: 'school')]
    public function school(Request $request, UserRepository $userRepository, UserProfileRepository $repository, SchoolRepository $schoolRepository, int $page = 1): Response
    {
        if($request->isMethod('POST')) {
            $filterValues = $request->request->all();
            $request->getSession()->set('filter_school', $filterValues);
        }

        $search = $request->getSession()->get('filter_school')['person'] ?? null;
        $courses = $request->getSession()->get('filter_school')['course'] ?? null;
        $startDate = $request->getSession()->get('filter_school')['start_date'] ?? null;
        $endDate = $request->getSession()->get('filter_school')['end_date'] ?? null;

        $searchArray = explode(' ', $search);
        if(count($searchArray) > 1){
            $name = array_pop($searchArray);
            $firstname = implode(' ', $searchArray);
        } else {
            $firstname = false;
            $name = array_pop($searchArray);
        }

        $usedDates = $repository->findExamDates();


        if($this->isGranted('ROLE_USER')) {
            $user = $userRepository->find($this->getUser());
            if($school = $user->getSchool()) {
                $filtered = $request->getSession()->get('filter_school');
                $filtered['school'] = [$school->getId()];
                $request->getSession()->set('filter_school',$filtered);
                $people = $repository->findBySearchQuery($name, $firstname, [$school->getId()], $courses, $startDate, $endDate, offset: $page);
                $peopleCount = count($repository->findBySearchQuery($name, $firstname, [$school->getId()], $courses, $startDate, $endDate));
            } else {
                $this->addFlash('warning','Du hast dich keiner Bildungseinrichtung zugeordnet!');
                return $this->redirectToRoute('people_index');
            }
        } else {
            return $this->redirectToRoute('people_index');
        }

        $names = [
            "Anna Müller",
            "Carlos Rodríguez",
            "Fatima Al-Mansouri",
            "Yuki Tanaka",
            "Liam O'Connor",
            "Sophia Rossi",
            "Ivan Petrov",
            "Chen Wei",
            "Aisha Ndiaye",
            "Jakub Kowalski",
            "Emily Johnson",
            "Hassan Ahmed",
            "Leila Dupont",
            "Mateo Fernández",
            "Zhang Xiaoming",
            "Katarina Novak",
            "Raj Patel",
            "Mehmet Yılmaz",
            "Olga Ivanova",
            "Gustav Svensson"
        ];


        return $this->render('people/school.html.twig', [
            'filterValues' => $request->getSession()->get('filter_school'),
            'filter' => 'school',
            'page' => $page,
            'people' => $people,
            'people_count' => $peopleCount,
            'schools' => $schoolRepository->findAll(),
            'dates' => $usedDates,
            'courses' => PerformanceCourseEnum::cases(),
            'name' => $names[array_rand($names)],
        ]);
    }

}
