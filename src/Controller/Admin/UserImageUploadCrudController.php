<?php

namespace App\Controller\Admin;

use App\Entity\BlogPost;
use App\Entity\UserImageUpload;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class UserImageUploadCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return UserImageUpload::class;
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if($entityInstance instanceof UserImageUpload and !$entityInstance->getUser()) {
            $entityInstance->setUser($this->getUser());
        }
        parent::persistEntity($entityManager, $entityInstance);
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
            ->setPermission(Action::NEW, 'ROLE_AUTHOR')
            ->setPermission(Action::EDIT, 'ROLE_AUTHOR')
            ->setPermission(Action::DELETE, 'ROLE_ADMIN')
            ->setPermission(Action::BATCH_DELETE, 'ROLE_ADMIN')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            ImageField::new('image.name')
                ->setBasePath('/images/user_uploads')
                ->setLabel('Vorschau')
                ->onlyOnIndex(),
            TextField::new('image.name')->onlyOnIndex(),
            AssociationField::new('user')->onlyOnIndex(),

            TextField::new('imageFile')->onlyWhenUpdating()
            ->setFormType(VichImageType::class),

            TextField::new('imageFile')
                ->setFormType(VichImageType::class)
                ->setLabel('Bild hochladen')
                ->onlyWhenCreating(),
        ];
    }

}
