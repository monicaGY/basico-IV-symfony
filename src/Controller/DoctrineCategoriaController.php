<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Categoria;

class DoctrineCategoriaController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }

    #[Route('/api/v1/doctrine/categoria', methods:['GET'])]
    public function index(): JsonResponse
    {

        $datos = $this->em->getRepository(Categoria::class)->findBy(array(),array('id'=>'desc'));
        return $this->json($datos);
    }

    #[Route('/api/v1/doctrine/categoria/{id}', methods:['GET'])]
    public function buscar_id(int $id): JsonResponse
    {

        $datos = $this->em->getRepository(Categoria::class)->find($id);

        if(!$datos){
            return $this->json([
                'estado'=>'error',
                'mensaje'=>'Categoría no disponible'
            ]);
        }
        return $this->json($datos);
    }
}
