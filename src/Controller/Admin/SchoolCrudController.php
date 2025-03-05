<?php

namespace App\Controller\Admin;

use App\Entity\School;
use App\Operator\SoundExpression;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class SchoolCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return School::class;
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
            ->setPermission(Action::INDEX, 'ROLE_ADMIN')
            ;
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof School) {
            $entityInstance->setTitleSoundEx(SoundExpression::generate($entityInstance->getTitle()));
        }
        parent::persistEntity($entityManager, $entityInstance);
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title')->setRequired(true),
            TextField::new('bsn')->setRequired(true),
            TextField::new('street')->setRequired(true)->hideOnIndex(),
            TextField::new('plz')->setRequired(true)->hideOnIndex(),
            TextField::new('subDistrict')->hideOnIndex(),
            TextField::new('district')->hideOnIndex(),
            TextField::new('city')->setRequired(true),
            ChoiceField::new('county')
                ->setRequired(true)
            ->setChoices([
                'Baden-Württemberg' => 'Baden-Württemberg',
                'Bayern' => 'Bayern',
                'Berlin' => 'Berlin',
                'Brandenburg' => 'Brandenburg',
                'Bremen' => 'Bremen',
                'Hamburg' => 'Hamburg',
                'Hessen' => 'Hessen',
                'Mecklenburg-Vorpommern' => 'Mecklenburg-Vorpommern',
                'Niedersachsen' => 'Niedersachsen',
                'Nordrhein-Westfalen' => 'Nordrhein-Westfalen',
                'Rheinland-Pfalz' => 'Rheinland-Pfalz',
                'Saarland' => 'Saarland',
                'Sachsen' => 'Sachsen',
                'Sachsen-Anhalt' => 'Sachsen-Anhalt',
                'Schleswig-Holstein' => 'Schleswig-Holstein',
                'Thüringen' => 'Thüringen'
            ])
            ,
            TextField::new('lat')->setRequired(true)->hideOnIndex(),
            TextField::new('lon')->setRequired(true)->hideOnIndex(),
            UrlField::new('url')->setRequired(true),
        ];
    }

}
