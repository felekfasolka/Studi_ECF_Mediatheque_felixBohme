<?php

namespace App\Controller\Media;

use App\Entity\Book;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MediathequeDashboardController extends AbstractDashboardController
{
    /**
     * @Route("/media", name="media")
     * @IsGranted("ROLE_USER")
     */
    public function index(): Response
    {
        $routeBuilder = $this->get(AdminUrlGenerator::class);
        $url = $routeBuilder->setController(MediaCrudController::class)->generateUrl();
        if (in_array('ROLE_EDITOR', $this->getUser()->getRoles(), true)) {
            return $this->redirect($this->generateUrl('backend'));}
        else {
            return $this->redirect($url);
            }
        }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('<i class="fab fa-medium-m"></i>ediatheque de<br>La Chapelle-Curreaux');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('Mediatheque');
        yield MenuItem::linkToCrud('Available Items', 'fas fa-book-open', Book::class)->setController(MediaCrudController::class);
        yield MenuItem::linkToCrud('My Reserved Items', 'fas fa-bookmark', Book::class)->setController(MediaReservedCrudController::class);
        yield MenuItem::linkToCrud('My Borrowed Items', 'fas fa-book-reader', Book::class)->setController(MediaBorrowedCrudController::class);
        yield MenuItem::linkToCrud('My Dues', 'fas fa-hourglass-half', Book::class)->setController(MediaDueCrudController::class);
        yield MenuItem::section('');
        yield MenuItem::linkToLogout('Logout', 'fas fa-sign-out-alt');
    }

    public function configureAssets(): Assets
    {
        return Assets::new()->addCssFile('css/employeeDash.css');
    }
}
