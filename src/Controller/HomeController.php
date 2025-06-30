<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function index(TrickRepository $repository): Response
    {
        $tricks = $repository->findBy([], ['createdAt' => 'DESC'], 15);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'tricks' => $tricks,
        ]);
    }

    #[Route('/load-tricks', name: 'load_tricks', methods: ['GET'])]
    public function loadTricks(TrickRepository $repository, Request $request): JsonResponse
    {
        $offset = $request->query->getInt('offset', 0);
        $limit = 10;

        $tricks = $repository->findBy([], ['createdAt' => 'DESC'], $limit, $offset);
        
        return $this->json([
            'html' => $this->renderView('home/_trick_cards.html.twig', [
                'tricks' => $tricks,
            ]),
            'hasMore' => count($tricks) === $limit,
        ]);
    }

}
