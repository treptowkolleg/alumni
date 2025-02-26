<?php

namespace App\Controller\Admin;

use App\Entity\Inbound;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class InboundCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Inbound::class;
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
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->setPermission(Action::INDEX, 'ROLE_MODERATION')
            ;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            DateTimeField::new('created'),
            TextField::new('subject'),
            EmailField::new('sender'),
            EmailField::new('departement'),
            FormField::addPanel('Text-Ansicht')->collapsible()->renderCollapsed(),
            TextareaField::new('text')->hideOnIndex(),
            FormField::addPanel('HTML-Ansicht')->collapsible()->renderCollapsed(),
            TextareaField::new('html')->hideOnIndex()->renderAsHtml(),
        ];
    }

}
