<?php

namespace App\DataFixtures;

use App\Entity\EventType;
use App\Entity\School;
use App\Entity\User;
use App\Entity\UserProfile;
use App\Operator\SoundExpression;
use App\Repository\SchoolRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $passwordHasher;
    private $schoolRepository;
    public function __construct(UserPasswordHasherInterface $passwordHasher, SchoolRepository $schoolRepository)
    {
        $this->passwordHasher = $passwordHasher;
        $this->schoolRepository = $schoolRepository;
    }

    public function load(ObjectManager $manager): void
    {

        $schools = $this->schoolRepository->findAll();

        $testUsers = [];
        for ($i = 0; $i < 30; $i++) {
            $randomFirstName = 'User' . $i; // Zufälliger Vorname
            $randomLastName = 'Last' . $i; // Zufälliger Nachname
            $randomEmail = strtolower($randomLastName) . '@local.domain'; // Email basiert auf Nachname

            $testUsers[] = [
                'firstname' => $randomFirstName,
                'lastname' => $randomLastName,
                'email' => $randomEmail,
                'password' => 'testPassword',
                'isVerified' => true,
                'userType' => 'Student',
                'hasSchool' => true,
            ];
        }

        $names = [
            ['John', 'Smith'],
            ['Jane', 'Doe'],
            ['Michael', 'Johnson'],
            ['Emily', 'Davis'],
            ['Chris', 'Brown'],
            ['Sarah', 'Miller'],
            ['David', 'Wilson'],
            ['Laura', 'Moore'],
            ['James', 'Taylor'],
            ['Jessica', 'Anderson'],
            ['Daniel', 'Thomas'],
            ['Sophia', 'Jackson'],
            ['Matthew', 'White'],
            ['Olivia', 'Harris'],
            ['Andrew', 'Martin'],
            ['Emma', 'Thompson'],
            ['Joshua', 'Garcia'],
            ['Mia', 'Martinez'],
            ['Ryan', 'Robinson'],
            ['Isabella', 'Clark'],
            ['Tyler', 'Rodriguez'],
            ['Ava', 'Lewis'],
            ['Nathan', 'Lee'],
            ['Amelia', 'Walker'],
            ['Ethan', 'Hall'],
            ['Lily', 'Allen'],
            ['Jacob', 'Young'],
            ['Ella', 'King'],
            ['Samuel', 'Wright'],
            ['Zoe', 'Scott']
        ];


        foreach ($testUsers as $testUser) {
            $user = new User();
            $name = array_shift($names);
            $user->setFirstName($name[0]);
            $user->setLastName($name[1]);
            $user->setEmail($testUser['email']);
            $user->setPassword($this->passwordHasher->hashPassword($user, $testUser['password']));
            $schoolNr = rand(0, count($schools)-1);
            $user->setSchool($schools[$schoolNr]);
            $user->setFirstnameSoundEx(SoundExpression::generate($user->getFirstname()));
            $user->setLastnameSoundEx(SoundExpression::generate($user->getLastname()));
            $user->setUserType($testUser['userType']);
            $user->setHasSchool($testUser['hasSchool']);
            $manager->persist($user);

            $userProfile = new UserProfile();
            $userProfile->setDisplayName('fullnameEx');
            $userProfile->setHobbies([]);
            $userProfile->setNetworkState('public');
            $userProfile->setFavoriteSchoolSubjects([]);
            $userProfile->setPerformanceCourse([]);
            $userProfile->setInterests([]);
            $userProfile->setUser($user);
            $userProfile->setInYear(rand(1991,2026));
            $userProfile->setOutYear($userProfile->getInYear()+3);
            $manager->persist($userProfile);
        }

        $manager->flush();
    }
}
