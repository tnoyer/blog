<?php

namespace App\Controller;

use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
