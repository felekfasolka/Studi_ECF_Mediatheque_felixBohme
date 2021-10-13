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
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Vich\UploaderBundle\Form\Type\VichImageType;

class MediaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Book::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Available Mediatheque Item')
            ->setEntityLabelInPlural('Available Mediatheque Items')
            ->setSearchFields(['title', 'author', 'genre.type'])
            ->setDefaultSort(['dateOfPublication' => 'ASC'])
            ->showEntityActionsInlined()
            ->setHelp('index', 'This section shows the available Mediatheque Items.')
            ->setPageTitle('edit','Request this Item');
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('genre')->setLabel('Genre'));
    }


    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('title')->setDisabled();
        yield TextField::new('author')->hideOnForm();
        yield TextField::new('imageFile')->setFormType(VichImageType::class)->hideOnIndex()->hideOnForm()->hideOnDetail();
        yield ImageField::new('coverPicture')->setBasePath('/uploads/images/media/')->hideOnForm();
        yield TextField::new('description')->onlyOnDetail();
        yield AssociationField::new('genre')->hideOnForm();
        yield DateField::new('dateOfPublication')->hideOnForm();
    }

    public function configureActions(Actions $actions): Actions
    {

        return $actions
            ->setPermission(Action::NEW, 'ROLE_EDITOR')
            ->setPermission(Action::DELETE, 'ROLE_EDITOR')
            ->setPermission(Action::DETAIL, 'ROLE_USER')
            ->setPermission(Action::EDIT, 'ROLE_USER')
            ->setPermission(Action::BATCH_DELETE, 'ROLE_EDITOR')
            ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action)
            {
                return $action->setLabel('Reserve')->setIcon('fa fa-star')->addCssClass('text-success');
            })
            ->add(Crud::PAGE_INDEX,Action::DETAIL)
            ->update(Crud::PAGE_DETAIL,Action::EDIT, function (Action $action)
            {
                return $action->setLabel('Go to Reservation')->setIcon('fa fa-star');
            })
            ->update(Crud::PAGE_EDIT,Action::SAVE_AND_RETURN, function (Action $action)
            {
                return $action->setLabel('Reserve')->setIcon('fa fa-star')->addCssClass('btn-lg');
            })
            ->update(Crud::PAGE_INDEX,Action::DETAIL, function (Action $action)
            {
                return $action->setLabel('Show')->setIcon('fas fa-eye')->addCssClass('text-warning');
            })
            ->remove(Crud::PAGE_EDIT,Action::SAVE_AND_CONTINUE);
    }

    protected function getRedirectResponseAfterSave(AdminContext $context, string $action): RedirectResponse
    {
        return $this->redirectToRoute('media');
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $qb = $this->get(EntityRepository::class)->createQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $qb->andWhere('entity.isBorrowedBy IS NULL');

        return $qb;
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {

        $entityInstance->setIsBorrowedAt(new \DateTime());
        $entityInstance->setIsBorrowedBy($this->getUser());
        $entityInstance->setIsRequested(true);
        $entityInstance->setIsConfirmed(false);
        parent::updateEntity($entityManager, $entityInstance);
        $this->addFlash('success', 'Your Reservation Request has been recorded. Please find it under MY RESERVED ITEMS');
    }
}


