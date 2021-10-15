<?php

namespace App\Controller\Media;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
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

class MediaReservedCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Book::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('My Reserved Item')
            ->setEntityLabelInPlural('My Reserved Items')
            ->setSearchFields(['title', 'author', 'genre.type'])
            ->setDefaultSort(['isBorrowedAt' => 'DESC'])
            ->showEntityActionsInlined()
            ->setHelp('index', 'This section shows your reserved Mediatheque Items.')
            ->setPageTitle('edit','Delete your Reservation');
    }


    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('title')->setDisabled();
        yield TextField::new('author')->hideOnForm();
        yield TextField::new('imageFile')->setFormType(VichImageType::class)->hideOnIndex()->hideOnForm();
        yield ImageField::new('coverPicture')->setBasePath('/uploads/images/media/')->onlyOnIndex();
        yield AssociationField::new('genre')->hideOnForm();
        yield DateField::new('dateOfPublication')->hideOnForm();
        yield DateField::new('isBorrowedAt')->setLabel('Borrowed At')->hideWhenUpdating();
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->setPermission(Action::NEW, 'ROLE_EDITOR')
            ->setPermission(Action::DELETE, 'ROLE_SUPER')
            ->setPermission(Action::DETAIL, 'ROLE_EDITOR')
            ->setPermission(Action::EDIT, 'ROLE_USER')
            ->setPermission(Action::BATCH_DELETE, 'ROLE_EDITOR')
            ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action)
            {
                return $action->setLabel('Delete Reservation')->setIcon('far fa-trash-alt')->addCssClass('text-danger');
            })
            ->update(Crud::PAGE_EDIT,Action::SAVE_AND_RETURN, function (Action $action)
            {
                return $action->setLabel('Delete')->setIcon('far fa-trash-alt')->addCssClass('btn-lg');
            })
            ->remove(Crud::PAGE_EDIT,Action::SAVE_AND_CONTINUE);

    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $qb = $this->get(EntityRepository::class)->createQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $qb->andWhere('entity.isBorrowedBy = :id');
        $qb->andWhere('entity.isConfirmed = false');
        $qb->setParameter('id', $this->getUser()->getId());

        return $qb;
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->setIsBorrowedAt(new \DateTime('1900-01-01'));
        $entityInstance->setIsBorrowedBy(NULL);
        $entityInstance->setIsRequested(false);
        $entityInstance->setIsConfirmed(false);
        parent::updateEntity($entityManager, $entityInstance);
        $this->addFlash('success', 'Your Reservation has been deleted!');
    }

}
