<?php

namespace App\Controller\Employee;

use App\Entity\Book;
use App\Entity\Genre;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BackendDashboardController extends AbstractDashboardController
{
    /**
     * @Route("/emp", name="backend")
     * @IsGranted("ROLE_EDITOR")
     */
    public function index(): Response
    {
        $routeBuilder = $this->get(AdminUrlGenerator::class);
        $url = $routeBuilder->setController(EmpMediaCrudController::class)->generateUrl();
        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('<i class="fab fa-medium-m"></i>ediatheque de<br>La Chapelle-Curreaux<br><i class="fas fa-user-lock"></i>');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('Mediatheque');
        yield MenuItem::linkToCrud('Mediatheque', 'fas fa-book-open', Book::class)->setController(EmpMediaCrudController::class);
        yield MenuItem::linkToCrud('Borrowed Items', 'fas fa-book-reader', Book::class)->setController(EmpMediaBorrowedCrudController::class);
        yield MenuItem::section('Tasks');
        yield MenuItem::linkToCrud('Issue Desk', 'fas fa-hand-holding', Book::class)->setController(EmpMediaWaitingCrudController::class);
        yield MenuItem::linkToCrud('Due Items', 'fas fa-hourglass-half', Book::class)->setController(EmpMediaDueCrudController::class);
        yield MenuItem::linkToCrud('Return Desk', 'fas fa-undo-alt', Book::class)->setController(EmpMediaReturnCrudController::class);
        yield MenuItem::section('Other');
        yield MenuItem::linkToCrud('Genres', 'fas fa-map-marker-alt', Genre::class);
        yield MenuItem::linkToCrud('User', 'fas fa-user', User::class);
        yield MenuItem::section('');
        yield MenuItem::linkToLogout('Logout', 'fas fa-sign-out-alt');
    }

    public function configureAssets(): Assets
    {
        return Assets::new()->addCssFile('css/employeeDash.css');
    }
}
