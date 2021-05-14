<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Users;
use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api", name="api_")
 */
class ApiController extends AbstractController
{
    /**
     * @Route("/articles/liste", name="liste", methods={"GET"})
     * @param ArticlesRepository $articlesRepo
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function liste(ArticlesRepository $articlesRepo, SerializerInterface $serializer)
    {
        // On récupère la liste des articles
        $articles = $articlesRepo->findAll();

        //on sérialize les données
        $jsonContent = $serializer->serialize(
            $articles,
            'json',
            ['groups' => 'show_articles']
        );

        // On instancie la réponse
        $response = new Response($jsonContent);

        // On ajoute l'entête HTTP
        $response->headers->set('Content-Type', 'application/json');

        // On envoie la réponse
        return $response;
    }

    /**
     * @Route("/article/lire/{id}", name="article", methods={"GET"})
     * @param Articles $article
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function getArticle(Articles $article, SerializerInterface $serializer)
    {
        $jsonContent = $serializer->serialize(
            $article,
            'json',
            ['groups' => ['show_articles', 'show_users']]
        );
        $response = new Response($jsonContent);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/article/ajout", name="ajout", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function addArticle(Request $request)
    {
        // On vérifie si la requête est une requête Ajax
        //if($request->isXmlHttpRequest()) {
            // On instancie un nouvel article
            $article = new Articles();

            // On décode les données envoyées
            $donnees = json_decode($request->getContent());

            // On hydrate l'objet
            $article->setTitre($donnees->titre);
            $article->setContenu($donnees->contenu);
            $article->setFeaturedImage($donnees->image);
            $user = $this->getDoctrine()->getRepository(Users::class)->findOneBy(["id" => 36]);
            $article->setUsers($user);

            // On sauvegarde en base
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            // On retourne la confirmation
            return new Response('ok', 201);
        //}
        //return new Response('Failed', 404);
    }

    /**
     * @Route("/article/editer/{id}", name="edit", methods={"PUT"})
     * @param Articles|null $article
     * @param Request $request
     * @return Response
     */
    public function editArticle(?Articles $article, Request $request)
    {
        // On vérifie si la requête est une requête Ajax
        //if($request->isXmlHttpRequest()) {

            // On décode les données envoyées
            $donnees = json_decode($request->getContent());

            // On initialise le code de réponse
            $code = 200;

            // Si l'article n'est pas trouvé
            if(!$article){
                // On instancie un nouvel article
                $article = new Articles();
                // On change le code de réponse
                $code = 201;
            }

            // On hydrate l'objet
            $article->setTitre($donnees->titre);
            $article->setContenu($donnees->contenu);
            $article->setFeaturedImage($donnees->image);
            $user = $this->getDoctrine()->getRepository(Users::class)->find(36);
            $article->setUsers($user);

            // On sauvegarde en base
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            // On retourne la confirmation
            return new Response('ok', $code);
        //}
        //return new Response('Failed', 404);
    }

    /**
     * @Route("/article/supprimer/{id}", name="supprime", methods={"DELETE"})
     * @param Articles $article
     * @return Response
     */
    public function removeArticle(Articles $article)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($article);
        $entityManager->flush();
        return new Response('ok');
    }
}
