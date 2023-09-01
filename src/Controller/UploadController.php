<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadController extends AbstractController
{
    #[Route('/upload', methods:['POST'])]
    public function methos_post(Request $request): JsonResponse
    {
        $foto = $request->files->get('foto');
        if($foto){
            $newFileName = time().'.'.$foto->guessExtension();

            try{

                $foto->move(
                    $this->getParameter('fotos_directory'),
                    $newFileName
                );
                return $this->json([
                    'estado' => 'ok',
                    'mensaje' => 'proceso completado',
                ]);
            }catch(\Throwable $th){
                // throw new \Exception("mensaje", "Ups, no se completo el proceso");

                return $this->json([
                    'estado' => 'error',
                    'mensaje' => 'Ups, no se completo el proceso',
                ], 400);
                

            }
        }

        return $this->json([
            'estado' => 'error',
            'mensaje' => 'Ups, no se completo el proceso',
        ], 400);
        
    }
}
