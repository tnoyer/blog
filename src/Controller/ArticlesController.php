<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Categories;
use App\Entity\Commentaires;
use App\Form\ArticleFormType;
use App\Form\CommentaireFormType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class ArticlesController
 * @package App\Controller
 * @Route("/actualites", name="actualites_")
 */
class ArticlesController extends AbstractController
{
    /**
     * @Route("/articles", name="articles")
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(Request $request, PaginatorInterface $paginator)
    {
        //liste des articles trié par date de création
        /*
        $donnees = $this->getDoctrine()->getRepository(Articles::class)->findBy([],
        ['created_at' => 'desc']);
        */

        //liste des catégories
        $categories = $this->getDoctrine()->getRepository(Categories::class)->findAll();

        //On récupère les filtres catégories
        $filters = $request->get("categories");

        // On définit le nombre d'éléments par page
        $limit = 8;

        //page courante
        $page = $request->query->getInt('page', 1);

        //liste des articles avec le filtre catégorie
        $articles = $this->getDoctrine()->getRepository(Articles::class)->getPaginatedArticles($page, $limit, $filters);

        // On récupère le nombre total d'articles
        $total = $this->getDoctrine()->getRepository(Articles::class)->getTotalArticles($filters);

        if($request->get('ajax')){
            return new JsonResponse([
                'content' => $this->renderView('articles/_content.html.twig', [
                    'articles' => $articles,
                    'page' => $page,
                    'total' => $total,
                    'limit' => $limit
                ])
            ]);
        }

        return $this->render('articles/index.html.twig', [
            'articles' => $articles,
            'categories' => $categories,
            'page' => $page,
            'total' => $total,
            'limit' => $limit
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/article/nouveau", name="ajout_article")
     */
    public function AjoutArticle(Request $request){
        $article = new Articles();
        $form = $this->createForm(ArticleFormType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $article->setUsers($this->getUser());

            $doctrine = $this->getDoctrine()->getManager();

            // On hydrate notre instance $commentaire
            $doctrine->persist($article);

            // On écrit en base de données
            $doctrine->flush();

            //message de succès
            $this->addFlash('success', 'L\'article a bien été ajouté!');

            // On redirige l'utilisateur
            return $this->redirectToRoute('actualites_articles');
        }

        return $this->render('articles/ajout.html.twig', [
            'articleForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/article/{slug}", name="article")
     */
    public function article($slug, Request $request){
        // On récupère l'article correspondant au slug
        $article = $this->getDoctrine()->getRepository(Articles::class)->findOneBy(['slug' => $slug]);

        // On récupère les commentaires actifs de l'article
        $commentaires = $this->getDoctrine()->getRepository(Commentaires::class)->findBy([
            'articles' => $article,
            'actif' => 1
        ],['created_at' => 'desc']);

        if(!$article){
            // Si aucun article n'est trouvé, nous créons une exception
            throw $this->createNotFoundException('L\'article n\'existe pas');
        }

        //on instancie l'entity Commentaires
        $commentaire = new Commentaires();
        //on crée l'objet formulaire
        $form = $this->createForm(CommentaireFormType::class, $commentaire);

        // Nous récupérons les données
        $form->handleRequest($request);

        // Nous vérifions si le formulaire a été soumis et si les données sont valides
        if ($form->isSubmitted() && $form->isValid()) {
            // Hydrate notre commentaire avec l'article
            $commentaire->setArticles($article);

            // Hydrate notre commentaire avec la date et l'heure courants
            $commentaire->setCreatedAt(new \DateTime('now'));

            $doctrine = $this->getDoctrine()->getManager();

            // On hydrate notre instance $commentaire
            $doctrine->persist($commentaire);

            // On écrit en base de données
            $doctrine->flush();

            //message de succès
            $this->addFlash('success', 'Le commentaire est en cous de validation!');

            // On redirige l'utilisateur
            return $this->redirectToRoute('actualites_article', ['slug' => $slug]);
        }

        return $this->render('articles/article.html.twig', [
            'article' => $article,
            'commentaires' => $commentaires,
            'commentForm' => $form->createView()
        ]);
    }
}
