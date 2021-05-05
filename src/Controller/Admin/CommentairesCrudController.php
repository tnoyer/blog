<?php

namespace App\Controller\Admin;

use App\Entity\Commentaires;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CommentairesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Commentaires::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $detailComment = Action::new('detailComment', 'Détail', 'fa fa-comment')
            ->linkToCrudAction(Crud::PAGE_DETAIL)
            ->addCssClass('btn btn-info')
        ;

        return $actions
            ->disable(Action::NEW, Action::EDIT)
            ->add(Crud::PAGE_INDEX, $detailComment)
            ;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
