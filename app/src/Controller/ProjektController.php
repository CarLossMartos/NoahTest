<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProjektController extends AbstractController
{
    #[Route('/projekt', name: 'app_projekt')]
    public function index(): Response
    {
        return $this->render('projekt/index.html.twig', [
            'controller_name' => 'ProjektController',
        ]);
    }
}
