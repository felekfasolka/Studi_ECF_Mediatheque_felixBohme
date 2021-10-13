<?php

namespace App\Controller\Employee;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
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
