<?php

namespace App\Controller\Admin;

use App\Entity\PinboardEntry;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PinboardEntryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PinboardEntry::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->showEntityActionsInlined()
            ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->disable(Action::NEW, Action::EDIT);
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('userProfile','receiver'),
            AssociationField::new('writer','sender'),
            TextField::new('content'),
        ];
    }

}
