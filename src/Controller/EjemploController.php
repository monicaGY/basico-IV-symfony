<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;

class EjemploController extends AbstractController
{
    
    #[Route('/ejemplo', methods:['GET'])]
    public function metodo_get(): JsonResponse
    {
        return $this->json([
            'estado' => 'ok',
            'mensaje' => 'método get',
        ], 200);
    }

    // https://127.0.0.1:8000/ejemplo-query-string/elefante?id=1
    #[Route('/ejemplo-query-string/{animal}', methods:['GET'])]
    public function metodo_get_query_string(Request $request, string $animal): JsonResponse
    {
        return $this->json([
            'estado' => 'ok',
            'mensaje' => 'método get | id = '.$request->query->get('id') .' | id = '.$_GET['id'] .' | animal = '.$animal,
        ], 200);
    }

    #[Route('/ejemplo/{id}', methods:['GET'])]
    public function metodo_get_id(int $id): JsonResponse
    {
        return $this->json([
            'estado' => 'ok',
            'mensaje' => 'método get | id = '.$id,
        ], 200);
    }

    #[Route('/ejemplo', methods:['POST'])]
    public function metodo_post(Request $request): JsonResponse
    {

        $data = json_decode($request->getContent(), true);
        return $this->json([
            'estado' => 'ok',
            'mensaje' => 'método post',
            'correo' => $data["correo"]
        ], 200);
    }

    #[Route('/ejemplo/{id}', methods:['PUT'])]
    public function metodo_put(int $id): JsonResponse
    {
        return $this->json([
            'estado' => 'ok',
            'mensaje' => 'método put',
        ]);
    }

    #[Route('/ejemplo/{id}', methods:['DELETE'])]
    public function metodo_delete(int $id): JsonResponse
    {
        return $this->json([
            'estado' => 'ok',
            'mensaje' => 'método delete',
        ], 200);
    }


}
