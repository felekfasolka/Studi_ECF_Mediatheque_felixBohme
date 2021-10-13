<?php

namespace App\Controller\Employee;

use App\Entity\Book;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use Symfony\Config\VichUploaderConfig;
use Vich\UploaderBundle\Form\Type\VichImageType;

class EmpMediaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Book::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Mediatheque Item')
            ->setEntityLabelInPlural('Mediatheque Items')
            ->setSearchFields(['title', 'author', 'genre.type'])
            ->setDefaultSort(['dateOfPublication' => 'ASC'])
            ->showEntityActionsInlined()
            ->setHelp('index', 'This section shows the entire Mediatheque');

    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('genre')->setLabel('Genre'));
    }


    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('title');
        yield TextField::new('author');
        yield TextField::new('imageFile')->setFormType(VichImageType::class)->hideOnIndex();
        yield ImageField::new('coverPicture')->setBasePath('/uploads/images/media/')->onlyOnIndex();
        yield AssociationField::new('genre');
        yield TextareaField::new('description')
            ->hideOnIndex();
        yield DateField::new('dateOfPublication');
        yield DateField::new('isBorrowedAt')
            ->hideWhenCreating()
            ->setLabel('borrowed at');
        yield AssociationField::new('isBorrowedBy')
            ->setLabel('borrowed by');
        yield BooleanField::new('isConfirmed')
            ->hideWhenCreating()
            ->setLabel('Confirmed?')
            ->hideOnIndex();

    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->setPermission(Action::DELETE, 'ROLE_ADMIN');

    }


}


