<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('email'),
            TextField::new('firstname'),
            TextField::new('lastname'),
            ChoiceField::new('roles')->setChoices([
                'ROLE_USER' => 'ROLE_USER',
                'ROLE_PLANNER' => 'ROLE_PLANNER',
                'ROLE_SUPER_PLANNER' => 'ROLE_SUPER_PLANNER',
                'ROLE_AUTHOR' => 'ROLE_AUTHOR',
                'ROLE_SUPER_AUTHOR' => 'ROLE_SUPER_AUTHOR',
                'ROLE_EDITOR' => 'ROLE_EDITOR',
                'ROLE_SUPER_EDITOR' => 'ROLE_SUPER_EDITOR',
                'ROLE_ADMIN' => 'ROLE_ADMIN',
            ])->allowMultipleChoices(),
        ];
    }

}
