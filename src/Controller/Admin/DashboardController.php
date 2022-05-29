<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Comment;
use App\Entity\Category;
use App\Entity\Resource;
use App\Controller\Admin\UserCrudController;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\Admin\CommentCrudController;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Admin\CategoryCrudController;
use App\Controller\Admin\ResourceCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    public function __construct(private AdminUrlGenerator $adminUrlGenerator)
    {

    }
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        //return parent::index();

        $url = $this->adminUrlGenerator
            ->setController(CategoryCrudController::class)
            ->setController(ResourceCrudController::class)
            ->setController(CommentCrudController::class)
            ->setController(UserCrudController::class)
            ->generateUrl();
            return $this->redirect($url);
       
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
            ->setTitle('REssources RElationnelles');
    }

    public function configureMenuItems(): iterable
    {
       
        yield MenuItem::subMenu('Utilisateur','fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('Ajout Utilisateur','fas fa-plus',User::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Liste Utilisateurs','fas fa-eye',User::class)
        ]);
        yield MenuItem::subMenu('Ressource','fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('Ajout Resources','fas fa-plus',Resource::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Liste Resources','fas fa-eye',Resource::class)
        ]);
        yield MenuItem::subMenu('Categorie','fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('Ajout Categorie','fas fa-plus',Category::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Liste Categories','fas fa-eye',Category::class)
        ]);
        yield MenuItem::subMenu('Commentaires','fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('Ajout Commentaire','fas fa-plus',Comment::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Liste Commentaires','fas fa-eye',Comment::class)
        ]);
       
        //yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
