<?php

namespace App\Controller\Admin;

use App\Entity\Articles;
use App\Entity\Categories;
use App\Entity\MotsCles;
use App\Repository\ArticlesRepository;
use App\Repository\UsersRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class DashboardController extends AbstractDashboardController
{
    protected $usersRepository;
    protected $articlesRepository;

    public function __construct(UsersRepository $usersRepository, ArticlesRepository $articlesRepository)
    {
        $this->usersRepository = $usersRepository;
        $this->articlesRepository = $articlesRepository;
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return $this->render('bundles/EasyAdminBundle/welcome.html.twig', [
            'countUsers' => $this->usersRepository->countAllUsers(),
            'countArticles' => $this->articlesRepository->countAllArticles(),
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Gestion de mon blog');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),
            MenuItem::linkToRoute('Accueil', 'fa fa-home', 'home'),

            MenuItem::section('Articles'),
            MenuItem::linkToCrud('Articles', 'fa fa-book', Articles::class),
            MenuItem::linkToCrud('Categories', 'fa fa-tags', Categories::class),
            MenuItem::linkToCrud('Mots-cles', 'fa fa-tags', MotsCles::class),
        ];
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)
            ->setName($user->getUsername())
            ->setGravatarEmail($user->getUsername())
            ->displayUserAvatar(true)
            ;
    }
}
