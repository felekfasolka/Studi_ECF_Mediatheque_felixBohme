<?php

namespace App\Controller\Employee;

use App\Entity\Book;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use Vich\UploaderBundle\Form\Type\VichImageType;

class EmpMediaWaitingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Book::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Waiting Item')
            ->setEntityLabelInPlural('Waiting Items')
            ->setSearchFields(['title', 'author', 'genre.type'])
            ->setDefaultSort(['dateOfPublication' => 'ASC'])
            ->showEntityActionsInlined()
            ->setHelp('index', 'This section shows all Items waiting for Confirmation');;

    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('isBorrowedBy')->setLabel('Borrowed By'));
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
            ->setLabel('borrowed by')
            ->setPermission('ROLE_EDITOR');
        yield BooleanField::new('isConfirmed')
            ->hideWhenCreating()
            ->setLabel('Picked Up?')
            ->setPermission('ROLE_EDITOR');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->disable(Action::NEW, Action::DELETE);
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $qb = $this->get(EntityRepository::class)->createQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $qb->andWhere('entity.isBorrowedBy IS NOT NULL');
        $qb->andWhere('entity.isConfirmed = 0');
        return $qb;
    }

}


