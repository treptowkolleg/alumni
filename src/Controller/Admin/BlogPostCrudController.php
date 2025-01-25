<?php

namespace App\Controller\Admin;

use App\Entity\BlogPost;
use App\Repository\BlogTypeRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class BlogPostCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return BlogPost::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            // Index
            BooleanField::new('isPublished')->onlyOnIndex(),
            BooleanField::new('isLeadPost')->onlyOnIndex(),
            TextField::new('title')->onlyOnIndex(),
            TextField::new('subtitle')->onlyOnIndex(),

            // Form
            // Einstellungen
            FormField::addTab('Allgemein')->onlyOnForms(),
            // Panel Settings
            FormField::addPanel("Einstellungen"),
            AssociationField::new('type')->setRequired(true)->setColumns(6)->onlyOnForms()
                ->setFormTypeOption('query_builder', function (BlogTypeRepository $repository) {
                    return $repository->createQueryBuilder('e')
                        ->orderBy('e.title', 'ASC'); // Order by the 'title' field in ascending order
                }),
            AssociationField::new('author')->setRequired(true)->setColumns(6)->onlyOnForms(),
            BooleanField::new('isPublished')->setColumns(2)->onlyOnForms(),
            BooleanField::new('isLeadPost')->onlyOnForms()->setColumns(10),

            // Panel Content
            FormField::addPanel("Beitragsinhalt"),
            TextField::new('title')->setColumns(12)->onlyOnForms(),
            TextField::new('subtitle')->setColumns(9)->onlyOnForms()->setRequired(true),
            TextEditorField::new('content')->onlyOnForms()
                ->setColumns(12),
            ImageField::new('blogPostImage')->onlyOnForms()
                ->setBasePath('images/blogpost')
                ->setUploadDir('public/images/blogpost')
                ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]'),
            TextField::new('imageCite')->setColumns(6)->onlyOnForms(),
            TextField::new('imageCityUrl')->setColumns(6)->onlyOnForms(),

        ];
    }

}
