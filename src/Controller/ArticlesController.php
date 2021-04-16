<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Commentaires;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ArticlesController
 * @package App\Controller
 * @Route("/actualites", name="actualités_")
 */
class ArticlesController extends AbstractController
{
    /**
     * @Route("/articles", name="articles")
     */
    public function index(Request $request, PaginatorInterface $paginator)
    {
        //liste des articles trié par date de création
        $donnees = $this->getDoctrine()->getRepository(Articles::class)->findBy([],
        ['created_at' => 'desc']);

        //pagination
        $articles = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            4
        );

        return $this->render('articles/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/{slug}", name="slug")
     */
    public function article($slug, Request $request){
        //on récupère l'article correspondant au slug
        $article = $this->getDoctrine()->getRepository(Articles::class)->findOneBy(['slug' => $slug]);
        $commentaires = $this->getDoctrine()->getRepository(Commentaires::class)->findBy([],
            ['articles' => $article,
            'actif' => 1],
            ['created_at' => 'desc']
        );
        /*
        if(!$article){

        }
        */
        return $this->render('articles/article.html.twig', [
            'article' => $article,
        ]);
    }
}
