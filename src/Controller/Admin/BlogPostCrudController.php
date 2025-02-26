<?php

namespace App\Controller\Admin;

use App\Entity\BlogPost;
use App\Repository\BlogTypeRepository;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;

class BlogPostCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return BlogPost::class;
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
            ;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $queryBuilder = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);


        // Beispiel: Nur Produkte anzeigen, die aktiv sind oder für den Benutzer relevant
        if ($this->isGranted('ROLE_SUPER_AUTHOR')) {
            // Admins sehen alle Produkte
            return $queryBuilder;
        }

        // Wenn der Benutzer kein Admin ist, zeige nur Produkte an, die ihm gehören sind
        $queryBuilder->andWhere('entity.author = :user')
            ->setParameter('user', $this->getUser());

        return $queryBuilder;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            // Index
            TextField::new('title')->onlyOnIndex(),
            AssociationField::new('type')->onlyOnIndex(),
            BooleanField::new('isPublished')->onlyOnIndex(),
            BooleanField::new('isLeadPost')->onlyOnIndex(),
            AssociationField::new('author')->onlyOnIndex(),

            // Form
            // Einstellungen
            FormField::addTab('Allgemein')->onlyOnForms(),
            // Panel Settings
            FormField::addColumn('col-xl-9'),
            TextField::new('title')->onlyOnForms(),
            TextField::new('subtitle')->onlyOnForms()->setRequired(true),
            TextEditorField::new('content')->onlyOnForms(),

            FormField::addColumn('col-xl-3','Einstellungen'),
            FormField::addPanel(),
            AssociationField::new('type')->setRequired(true)->onlyOnForms()
                ->setFormTypeOption('query_builder', function (BlogTypeRepository $repository) {
                    return $repository->createQueryBuilder('e')
                        ->orderBy('e.title', 'ASC'); // Order by the 'title' field in ascending order
                }),
            AssociationField::new('author')->setRequired(true)->onlyOnForms()->setPermission('ROLE_ADMIN'),
            BooleanField::new('isPublished')->onlyOnForms(),
            BooleanField::new('isLeadPost')->onlyOnForms(),


            FormField::addTab('Beitragsbild')->onlyOnForms(),
            FormField::addColumn('col-xl-9'),
            ImageField::new('blogPostImage')->onlyOnForms()
                ->setBasePath('images/blogpost')
                ->setUploadDir('public/images/blogpost')
                ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]'),
            TextField::new('imageCite')->onlyOnForms(),
            TextField::new('imageCityUrl')->onlyOnForms(),




            FormField::addTab('Podcast')->onlyOnForms(),
            FormField::addColumn('col-xl-9'),
            ImageField::new('blogPostAudio')->onlyOnForms()
                ->setBasePath('audio/podcast')
                ->setUploadDir('public/audio/podcast')
                ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]')
                ->setFileConstraints(new File([
                    'maxSize' => '4096k',
                    'mimeTypes' => [
                        'audio/mpeg',
                        'audio/mp3',
                    ],
                    'mimeTypesMessage' => 'Please upload a valid mp3 file',
                ])),


        ];
    }

}
