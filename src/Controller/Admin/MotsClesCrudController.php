<?php

namespace App\Controller\Admin;

use App\Entity\MotsCles;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class MotsClesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MotsCles::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IntegerField::new('id')->onlyOnIndex(),
            TextField::new('mot_cle'),
            TextField::new('slug')->onlyOnIndex(),
        ];
    }
}
