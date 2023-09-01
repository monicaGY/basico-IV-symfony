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
    public function metodo_get(Request $request): JsonResponse
    {

        if($request->headers->get("x-token")){

            return $this->json([
                'estado' => 'ok',
                'mensaje' => 'método get',
                "mi header" => $request->headers->get("x-token")
            ], 200);
            
        }else{
            return $this->json([
                'estado' => 'ok',
                'mensaje' => 'método get',
            ], 200);

        }
    }

    #[Route('/ejemplo2', methods:['GET'])]
    public function metodo_get2(): Response
    {
        $response = new Response(json_encode(array(
            'estado'=>'ok',
            'mensaje'=>'mensaje desde get'
        )));

        $response->headers->set('Tamila','tamila.es');
        return $response;
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
