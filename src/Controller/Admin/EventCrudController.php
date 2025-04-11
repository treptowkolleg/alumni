<?php

namespace App\Controller\Admin;

use App\Entity\BlogPost;
use App\Entity\Event;
use App\Repository\BlogTypeRepository;
use App\Repository\EventTypeRepository;
use App\Repository\HobbyRepository;
use App\Repository\InterestRepository;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EventCrudController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return Event::class;
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
            ->setPermission(Action::INDEX, 'ROLE_PLANNER')
            ->setPermission(Action::NEW, 'ROLE_PLANNER')
            ->setPermission(Action::EDIT, 'ROLE_ADMIN')
            ->setPermission(Action::DELETE, 'ROLE_ADMIN')
            ->setPermission(Action::BATCH_DELETE, 'ROLE_ADMIN')
            ;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $queryBuilder = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);


        // Beispiel: Nur Produkte anzeigen, die aktiv sind oder für den Benutzer relevant
        if ($this->isGranted('ROLE_SUPER_PLANNER')) {
            // Admins sehen alle Produkte
            return $queryBuilder;
        }

        // Wenn der Benutzer kein Admin ist, zeige nur Produkte an, die ihm gehören sind
        $queryBuilder->andWhere('entity.user = :user')
            ->setParameter('user', $this->getUser());

        return $queryBuilder;
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if($entityInstance instanceof Event and !$entityInstance->getUser()) {
            $entityInstance->setUser($this->getUser());
        }
        parent::persistEntity($entityManager, $entityInstance);
    }


    public function configureFields(string $pageName): iterable
    {
        return [

            TextField::new('title')->onlyOnIndex(),
            AssociationField::new('type')->onlyOnIndex(),
            DateTimeField::new('startDate')->onlyOnIndex(),
            DateTimeField::new('endDate')->onlyOnIndex(),
            AssociationField::new('school')->onlyOnIndex(),
            AssociationField::new('user')->onlyOnIndex(),


            FormField::addTab('Allgemein')->onlyOnForms(),
            FormField::addColumn('col-xl-9'),
            TextField::new('title')->onlyOnForms(),
            TextEditorField::new('description')->onlyOnForms(),
            AssociationField::new('school')->setRequired(true)->setLabel('schools')->onlyOnForms()
                ->setFormTypeOption('query_builder', function (SchoolRepository $repository) {
                    return $repository->createQueryBuilder('e')
                        ->orderBy('e.title', 'ASC'); // Order by the 'title' field in ascending order
                }),
            AssociationField::new('user')->setRequired(true)->setPermission('ROLE_ADMIN')->onlyOnForms()
                ->setFormTypeOption('query_builder', function (UserRepository $repository) {
                    return $repository->createQueryBuilder('e')
                        ->orderBy('e.lastname', 'ASC'); // Order by the 'title' field in ascending order
                }),

            FormField::addColumn('col-xl-3','Einstellungen'),
            FormField::addPanel(),
            AssociationField::new('type')->setRequired(true)->onlyOnForms()
                ->setFormTypeOption('query_builder', function (EventTypeRepository $repository) {
                    return $repository->createQueryBuilder('e')
                        ->orderBy('e.title', 'ASC'); // Order by the 'title' field in ascending order
                }),
            TextField::new('website')->onlyOnForms()->setHelp('https://domain.de'),
            TextField::new('contactPerson')->onlyOnForms()->setHelp('Verantwortliche Personen'),
            EmailField::new('contactEmail')->onlyOnForms()->setHelp('E-Mail-Kontakt für Rückfragen (öffentlich)'),

            FormField::addTab('Ort und Zeit')->onlyOnForms(),
            FormField::addColumn('col-xl-9'),

            TextField::new('location')->onlyOnForms()->setHelp("Adresszeilen bitte kommagetrennt eingeben (z. B. Haus 13 Pfefferberg, Schönhauser Allee 176, 10119  Berlin)"),

            DateTimeField::new('startDate')->onlyOnForms(),
            DateTimeField::new('endDate')->onlyOnForms(),

            FormField::addTab('Interessen')->onlyOnForms(),
            FormField::addColumn('col-xl-9'),
            AssociationField::new('hobbies')->onlyOnForms()
                ->setFormTypeOptions([
                    'multiple' => true,
                    'by_reference' => false,
                    'query_builder' => function (HobbyRepository $er) {
                        return $er->createQueryBuilder('e')
                            ->leftJoin('e.category', 'c')
                            ->orderBy('c.label', 'ASC')
                            ->addOrderBy('e.label', 'ASC');
                    },
                    'group_by' => function($choice) {
                        return $choice->getCategory();
                    },
                    ])
                ->setCrudController(HobbyCrudController::class),
            AssociationField::new('interests')->onlyOnForms()
                ->setFormTypeOptions([
                    'multiple' => true,
                    'by_reference' => false,
                    'query_builder' => function (InterestRepository $er) {
                        return $er->createQueryBuilder('e')
                            ->leftJoin('e.category', 'c')
                            ->orderBy('c.label', 'ASC')
                            ->addOrderBy('e.label', 'ASC');
                    },
                    'group_by' => function($choice) {
                        return $choice->getCategory();
                    },
                    ])
                ->setCrudController(InterestCrudController::class),
        ];
    }

}
