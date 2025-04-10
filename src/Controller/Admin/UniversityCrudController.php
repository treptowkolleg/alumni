<?php

namespace App\Controller\Admin;

use App\Entity\University;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UniversityCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return University::class;
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
            ->setPermission(Action::NEW, 'ROLE_ADMIN')
            ->setPermission(Action::EDIT, 'ROLE_ADMIN')
            ->setPermission(Action::DELETE, 'ROLE_ADMIN')
            ->setPermission(Action::BATCH_DELETE, 'ROLE_ADMIN')
            ;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('shortTitle'),
            TextField::new('title'),
            TextField::new('county'),
            TextField::new('city'),
            TextField::new('plz'),
            TextField::new('street'),
            TextField::new('website'),
        ];
    }

}
