<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class EjemploController extends AbstractController
{
    #[Route('/ejemplo', name: 'app_ejemplo')]
    public function index(): JsonResponse
    {
        return $this->json([
            'estado' => 'ok',
            'mensaje' => 'primer mensaje',
        ], 200);
    }
}
