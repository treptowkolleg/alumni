<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Repository\BlogTypeRepository;
use App\Repository\EventTypeRepository;
use App\Repository\SchoolRepository;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
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


    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addPanel("Veranstaltungsdetails"),
            AssociationField::new('type')->setRequired(true)->setColumns(6)->onlyOnForms()
                ->setFormTypeOption('query_builder', function (EventTypeRepository $repository) {
                    return $repository->createQueryBuilder('e')
                        ->orderBy('e.title', 'ASC'); // Order by the 'title' field in ascending order
                }),
            AssociationField::new('school')->setRequired(true)->setColumns(6)->onlyOnForms()
                ->setFormTypeOption('query_builder', function (SchoolRepository $repository) {
                    return $repository->createQueryBuilder('e')
                        ->orderBy('e.title', 'ASC'); // Order by the 'title' field in ascending order
                }),
            TextField::new('title'),
            TextEditorField::new('description')->onlyOnForms(),
            TextField::new('website')->onlyOnForms(),
            TextField::new('location')->onlyOnForms(),
            TextField::new('contactPerson')->onlyOnForms(),
            EmailField::new('contactEmail')->onlyOnForms(),
            DateTimeField::new('startDate')->onlyOnForms(),
            DateTimeField::new('endDate')->onlyOnForms(),
            AssociationField::new('user')->setRequired(true)->setColumns(6)->onlyOnForms()
                ->setFormTypeOption('query_builder', function (UserRepository $repository) {
                    return $repository->createQueryBuilder('e')
                        ->orderBy('e.lastname', 'ASC'); // Order by the 'title' field in ascending order
                }),
        ];
    }

}
