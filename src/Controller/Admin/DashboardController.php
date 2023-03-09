<?php

namespace App\Controller\Admin;

use App\Entity\Announce;
use App\Entity\Category;
use App\Entity\Picture;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Annonce');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::subMenu('Categories', 'fas fa-newspaper')->setSubItems([
            MenuItem::linkToCrud('Toutes les categories', 'fas fa-List', Category::class),
            MenuItem::linkToCrud('Add', 'fas fa-plus', Category::class)->setAction(Crud::PAGE_NEW),
        ]);
        yield MenuItem::subMenu('Annonces', 'fas fa-newspaper')->setSubItems([
            MenuItem::linkToCrud('Toutes les annonces', 'fas fa-List', Announce::class),
            MenuItem::linkToCrud('Add', 'fas fa-plus', Announce::class)->setAction(Crud::PAGE_NEW),
        ]);
        yield MenuItem::subMenu('Images', 'fas fa-newspaper')->setSubItems([
            MenuItem::linkToCrud('Toutes les images', 'fas fa-List', Picture::class),
            MenuItem::linkToCrud('Add', 'fas fa-plus', Picture::class)->setAction(Crud::PAGE_NEW),
        ]);
        // yield MenuItem::linkToCrud('User', 'fas fa-list', User::class);

        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
