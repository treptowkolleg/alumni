<?php

namespace App\Controller\Admin;

use App\Entity\PersonOffer;
use App\Entity\School;
use App\Repository\SchoolRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints\Choice;

class PersonOfferCrudController extends AbstractCrudController
{


    public function __construct(private SchoolRepository $schoolRepository)
    {
    }

    public static function getEntityFqcn(): string
    {
        return PersonOffer::class;
    }


    public function configureFields(string $pageName): iterable
    {
        $schools = $this->schoolRepository->findAll();
        $counties = [];
        $cities = [];

        foreach ($schools as $school) {
            $counties[$school->getCounty()] = $school->getCounty();
            $cities[$school->getCity()] = $school->getCity();
        }

        return [
            TextField::new('title')->setRequired(true),
            AssociationField::new('type')->setRequired(true),
            TextEditorField::new('description')->setRequired(true),
            ChoiceField::new('county')->setRequired(true)
                ->setChoices($counties),
            ChoiceField::new('city')->setRequired(true)
                ->setChoices($cities),
        ];
    }

}
