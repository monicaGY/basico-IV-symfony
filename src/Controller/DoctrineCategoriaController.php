<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

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

    #[Route('/api/v1/doctrine/categoria', methods:['POST'])]
    public function crear_categoria(Request $request,SluggerInterface $slugger): JsonResponse
    {

        $datos = json_decode($request->getContent(), true);

        if(empty($datos['nombre'])){
            return $this->json([
                'estado' => 'error',
                'mensaje' => 'parametros vacíos'
            ], 200);
        }

        $categoria = new Categoria();

        $categoria->setNombre($datos['nombre']);
        $categoria->setSlug($slugger->slug(strtolower($datos['nombre'])));

        $this->em->persist($categoria);
        $this->em->flush();
        return $this->json([
            'estado'=>'ok',
            'mensaje'=>'Categoría creada con éxito'
        ], 201);
    }

    
}
