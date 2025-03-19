<?php

namespace App\Controller\Admin;

use App\Entity\NewsletterQueue;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class NewsletterQueueCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return NewsletterQueue::class;
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
            EmailField::new('receiverEmail'),
            DateTimeField::new('sendDate'),
            BooleanField::new('send'),
            AssociationField::new('template'),
        ];
    }

}
