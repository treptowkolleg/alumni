<?php

namespace App\Controller\Admin;

use App\Entity\PersonOffer;
use App\Entity\School;
use App\Repository\SchoolRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints\Choice;

class PersonOfferCrudController extends AbstractCrudController
{


    public function __construct(private SchoolRepository $schoolRepository, private UserRepository $userRepository)
    {
    }

    public static function getEntityFqcn(): string
    {
        return PersonOffer::class;
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
            ->setPermission(Action::INDEX, 'ROLE_SCHOOL')
            ->setPermission(Action::NEW, 'ROLE_SCHOOL')
            ->setPermission(Action::EDIT, 'ROLE_SCHOOL')
            ->setPermission(Action::DELETE, 'ROLE_SCHOOL')
            ->setPermission(Action::BATCH_DELETE, 'ROLE_SCHOOL')
            ;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $queryBuilder = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);


        // Beispiel: Nur Produkte anzeigen, die aktiv sind oder für den Benutzer relevant
        if ($this->isGranted('ROLE_ADMIN')) {
            // Admins sehen alle Produkte
            return $queryBuilder;
        }

        // Wenn der Benutzer kein Admin ist, zeige nur Produkte an, die ihm gehören sind
        $queryBuilder->andWhere('entity.user = :user')
            ->setParameter('user', $this->getUser());

        return $queryBuilder;
    }


    public function configureFields(string $pageName): iterable
    {
        $schools = $this->schoolRepository->findAll();
        $counties = [];
        $cities = [];

        foreach ($schools as $school) {
            $counties[$school->getCounty()] = $school->getCounty();
            $cities[$school->getCity()] = $school->getCity();
        }

        return [
            TextField::new('title')->setRequired(true),
            AssociationField::new('type')->setRequired(true),
            AssociationField::new('jobType')->setRequired(true),
            AssociationField::new('salaryLevel')->setRequired(true),
            TextField::new('department'),
            DateTimeField::new('startDate')
                ->setHelp('Erster Arbeitstag.')
                ->setRequired(true),
            DateTimeField::new('endDate')
                ->setHelp('Letzter Arbeitstag, insofern befristet.'),
            BooleanField::new('active'),
            TextEditorField::new('description')->setRequired(true)->hideOnIndex(),
            ChoiceField::new('county')->setRequired(true)->hideOnIndex()
                ->setChoices($counties),
            ChoiceField::new('city')->setRequired(true)->hideOnIndex()
                ->setChoices($cities),
            TextField::new('contactPerson')->setRequired(true),
            EmailField::new('contactEmail')->setRequired(true),
            TextField::new('contactPhone')->hideOnIndex(),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if($entityInstance instanceof PersonOffer) {
            $user = $this->userRepository->find($this->getUser());
            $entityInstance->setUser($user);
            $entityInstance->setSchool($user->getSchool());
        }
        parent::persistEntity($entityManager, $entityInstance);
    }

}
