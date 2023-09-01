<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class EjemploController extends AbstractController
{
    
    #[Route('/ejemplo', methods:['GET'], name: 'app_ejemplo')]
    public function metodo_get(): JsonResponse
    {
        return $this->json([
            'estado' => 'ok',
            'mensaje' => 'método get',
        ], 200);
    }

    #[Route('/ejemplo', methods:['POST'], name: 'app_ejemplo')]
    public function metodo_post(): JsonResponse
    {
        return $this->json([
            'estado' => 'ok',
            'mensaje' => 'método post',
        ], 200);
    }

    #[Route('/ejemplo', methods:['PUT'], name: 'app_ejemplo')]
    public function metodo_put(): JsonResponse
    {
        return $this->json([
            'estado' => 'ok',
            'mensaje' => 'método put',
        ]);
    }

    #[Route('/ejemplo', methods:['DELETE'], name: 'app_ejemplo')]
    public function metodo_delete(): JsonResponse
    {
        return $this->json([
            'estado' => 'ok',
            'mensaje' => 'método delete',
        ], 200);
    }


}
