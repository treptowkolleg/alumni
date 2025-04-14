<?php

namespace App\Controller\Admin;

use App\Entity\BlogPost;
use App\Entity\NewsletterTemplate;
use App\Entity\School;
use App\Repository\SchoolRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class NewsletterTemplateCrudController extends AbstractCrudController
{

    public function __construct(
        private UserRepository $userRepository,
    )
    {
    }

    public static function getEntityFqcn(): string
    {
        return NewsletterTemplate::class;
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
            ->setPermission(Action::EDIT, 'ROLE_ADMIN')
            ->setPermission(Action::DELETE, 'ROLE_ADMIN')
            ->setPermission(Action::BATCH_DELETE, 'ROLE_ADMIN')
            ;
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if($entityInstance instanceof NewsletterTemplate and !$entityInstance->getUser()) {
            $entityInstance->setUser($this->getUser());
        }

        if($entityInstance instanceof NewsletterTemplate and $entityInstance->getSchool()->count() == 0) {
            $user = $this->userRepository->find($this->getUser());
            $entityInstance->addSchool($user->getSchool());
        }

        if($entityInstance instanceof NewsletterTemplate and $entityInstance->isUseAllReceivers()) {
            $user = $this->userRepository->find($this->getUser());
            $entityInstance->addSchool($user->getSchool());
        }

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function configureFields(string $pageName): iterable
    {
        return [

            FormField::addTab('Allgemein'),
            TextField::new('title')->setHelp('Für den einleitenden Block im Newsletter.'),
            TextEditorField::new('description')->hideOnIndex()->setHelp('Für den einleitenden Block im Newsletter.'),
            AssociationField::new('school')->setRequired(true)->setLabel('schools')->onlyOnForms()
                ->setFormTypeOption('query_builder', function (SchoolRepository $repository) {
                    return $repository->createQueryBuilder('e')
                        ->orderBy('e.title', 'ASC'); // Order by the 'title' field in ascending order
                })->setFormTypeOptions([
                    'group_by' => function (School $school) {
                        return $school->getCounty();
                    },
                ])->setPermission('ROLE_ADMIN')
            ->setHelp('Nur für Admins sichtbar. Ansonsten immer die eigene Schule.'),

            FormField::addTab('Versand'),
            FormField::addColumn('col-xl-12'),
            DateTimeField::new('startDate')->setHelp('Erstmaliger Versand.'),
            FormField::addColumn('col-xl-2'),
            IntegerField::new('period'),
            FormField::addColumn('col-xl-2'),
            ChoiceField::new('periodUnit')->setChoices([
                'daily' => 'd',
                'weekly' => 'w',
                'monthly' => 'm',
                'yearly' => 'y',
            ]),
            FormField::addColumn('col-xl-12'),
            BooleanField::new('useAllReceivers')->onlyOnForms()
                ->setPermission('ROLE_ADMIN')
                ->setHelp('Nur für Admins sichtbar. Sendet an alle Empfänger.'),

            FormField::addTab('Optionen'),
            BooleanField::new('showRecentPins')->onlyOnForms(),
            BooleanField::new('showEvents')->onlyOnForms(),
            BooleanField::new('showRecentPosts')->onlyOnForms(),
            BooleanField::new('showRecentNews')->onlyOnForms(),
            BooleanField::new('showOffers')->onlyOnForms(),


        ];
    }

}
