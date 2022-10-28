<?php

namespace App\Controller;

use App\Repository\BoardRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(BoardRepository $boardRepository): Response
    {
        return $this->render('main/index.html.twig', [
            'boards' => $boardRepository->findAll(),
        ]);
    }
}
