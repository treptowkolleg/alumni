<?php

namespace App\Controller\Admin;

use App\Entity\BlogPostCategory;
use App\Repository\BlogPostCategoryRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class BlogPostCategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return BlogPostCategory::class;
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
            ->setPermission(Action::INDEX, 'ROLE_AUTHOR')
            ->setPermission(Action::NEW, 'ROLE_ADMIN')
            ->setPermission(Action::EDIT, 'ROLE_ADMIN')
            ->setPermission(Action::DELETE, 'ROLE_ADMIN')
            ->setPermission(Action::BATCH_DELETE, 'ROLE_ADMIN')
            ;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('label'),
            AssociationField::new('parent')->setRequired(false)->onlyOnForms()
                ->setFormTypeOption('query_builder', function (BlogPostCategoryRepository $repository) {
                    return $repository->createQueryBuilder('e')
                        ->orderBy('e.label', 'ASC'); // Order by the 'title' field in ascending order
                }),
        ];
    }

}
