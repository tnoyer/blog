<?php

namespace App\Controller\Admin;

use App\Entity\Articles;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FOS\CKEditorBundle\Config\CKEditorConfiguration;
use FOS\CKEditorBundle\Twig\CKEditorExtension;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ArticlesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Articles::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $imageFile = Field::new('imageFile')
            ->setFormType(VichImageType::class)
            ->setTranslationParameters(['form.label.delete'=>'Supprimer'])
            ->setLabel('Image');

        $image = ImageField::new('featured_image')
            ->setBasePath("/uploads/images/featured")
            ->setLabel('Image');

        $fields = [
            IntegerField::new('id')->onlyOnIndex(),
            TextField::new('titre'),
            TextEditorField::new('contenu')->hideOnIndex(),
            AssociationField::new('users'), //définir function __toString() ds entity Users
            AssociationField::new('categories'), //définir function __toString() ds entity Categories
            AssociationField::new('mots_cles'), //définir function __toString() ds entity motsCles
            DateTimeField::new('created_at')->onlyOnIndex(),
        ];

        if($pageName === Crud::PAGE_INDEX || $pageName === Crud::PAGE_DETAIL){
            $fields[] = $image;
        } else{
            $fields[] = $imageFile;
        }

        return $fields;
    }

}
