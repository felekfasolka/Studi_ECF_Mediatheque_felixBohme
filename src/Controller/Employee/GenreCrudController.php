<?php

namespace App\Controller\Employee;

use App\Entity\Genre;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;


class GenreCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Genre::class;
    }


    public function configureActions(Actions $actions): Actions
    {

        return $actions
            ->setPermission(Action::INDEX, 'ROLE_EDITOR')
            ->setPermission(Action::NEW, 'ROLE_EDITOR')
            ->setPermission(Action::DELETE, 'ROLE_ADMIN')
            ->setPermission(Action::DETAIL, 'ROLE_EDITOR')
            ->setPermission(Action::EDIT, 'ROLE_EDITOR')
            ->setPermission(Action::BATCH_DELETE, 'ROLE_EDITOR')
            ;
    }

}
