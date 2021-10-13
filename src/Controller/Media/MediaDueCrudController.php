<?php

namespace App\Controller\Media;

use App\Entity\Book;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use Vich\UploaderBundle\Form\Type\VichImageType;

class MediaDueCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Book::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('My Due Item')
            ->setEntityLabelInPlural('My Due Items')
            ->setSearchFields(['title', 'author', 'genre.type'])
            ->setDefaultSort(['isBorrowedAt' => 'ASC'])
            ->showEntityActionsInlined()
            ->setHelp('index', 'This section shows your borrowed Mediatheque Items which are due to bring back. These Items are with you over 3 weeks.');

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
            ->setLabel('Confirmed?')
            ->setPermission('ROLE_EDITOR');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->setPermission(Action::NEW, 'ROLE_SUPER')
            ->setPermission(Action::DELETE, 'ROLE_SUPER')
            ->setPermission(Action::DETAIL, 'ROLE_SUPER')
            ->setPermission(Action::EDIT, 'ROLE_SUPER')
            ->setPermission(Action::BATCH_DELETE, 'ROLE_SUPER');
    }


    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $qb = $this->get(EntityRepository::class)->createQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $qb->andWhere('entity.isBorrowedBy = :id');
        $qb->andWhere('entity.isBorrowedAt < :last');
        $qb->andWhere('entity.isConfirmed = true');
        $qb->setParameter('id', $this->getUser()->getId());
        $qb->setParameter('last', new \DateTime('-21 day'));

        return $qb;
    }
}
