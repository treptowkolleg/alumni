<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Operator\SoundExpression;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->showEntityActionsInlined()
            ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->setPermission(Action::INDEX, 'ROLE_ADMIN')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('email'),
            TextField::new('firstname'),
            TextField::new('lastname'),
            AssociationField::new('userProfiles')
                ->setLabel('Profil')
                ->onlyOnIndex() // oder where you need it
                ->formatValue(function ($value, $entity) {
                    // Wenn userProfiles eine Doctrine Collection ist:
                    $profiles = $entity->getUserProfiles();
                    if ($profiles && count($profiles) > 0) {
                        return $profiles->first(); // gibt z.B. den Namen oder die ID zurück
                    }
                    return null;
                }),
            AssociationField::new('school')
            ->setLabel('School')
            ->onlyOnIndex(),
            TextField::new('userType'),
            ChoiceField::new('roles')->setChoices([
                'ROLE_USER' => 'ROLE_USER',
                'ROLE_PLANNER' => 'ROLE_PLANNER',
                'ROLE_SUPER_PLANNER' => 'ROLE_SUPER_PLANNER',
                'ROLE_AUTHOR' => 'ROLE_AUTHOR',
                'ROLE_SUPER_AUTHOR' => 'ROLE_SUPER_AUTHOR',
                'ROLE_EDITOR' => 'ROLE_EDITOR',
                'ROLE_SUPER_EDITOR' => 'ROLE_SUPER_EDITOR',
                'ROLE_MODERATION' => 'ROLE_MODERATION',
                'ROLE_SUPER_MODERATION' => 'ROLE_SUPER_MODERATION',
                'ROLE_PROMOTER' => 'ROLE_PROMOTER',
                'ROLE_SCHOOL' => 'ROLE_SCHOOL',
                'ROLE_ADMIN' => 'ROLE_ADMIN',
            ])->allowMultipleChoices(),
            BooleanField::new('isVerified')->onlyOnIndex(),
            ChoiceField::new('userType')->onlyWhenCreating()->setFormTypeOptions([
                'choices' => ['student' => 'Student', 'teacher' => 'Teacher','employer' => 'Employer'],
            ]),
            AssociationField::new('school')->onlyWhenCreating()
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if($entityInstance instanceof User) {
            $user = $entityInstance;
            $user->setFirstnameSoundEx(SoundExpression::generate($user->getFirstname()));
            $user->setLastnameSoundEx(SoundExpression::generate($user->getLastname()));
            $user->setHasSchool(true);
            $user->setIsContactable(true);
            $user->setHasPinnboard(true);
            $user->setIsEventsVisible(true);
            $user->setPassword('$2y$13$jHU9Nox//Lz7t5kJOJKOzOFyiUofXF255jJacHV2t01igD/P13hOy');
        }
        parent::persistEntity($entityManager, $entityInstance);
    }

}
