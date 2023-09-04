<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Entity\ProductoFoto;

class DoctrineProductoFotoController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }


    #[Route('/doctrine/producto/foto', methods:['GET'])]
    public function listar_productos (): JsonResponse
    {

        $datos = $this->em->getRepository(ProductoFoto::class)->findBy(array(),array('id'=>'DESC'));

        $productos_array = [];

        foreach($datos as $dato){
            $productos_array[] = [
                'id' => $dato->getId(),
                'producto_id'=> $dato->getProducto()->getId(),
                'foto'=> 'https://127.0.0.1:8000/uploads/fotos/'.$dato->getFoto()
            ];
        }
        return $this->json($productos_array);
    }

    #[Route('/doctrine/producto/foto-descargar/{id}', methods:['GET'])]
    public function descargar_foto_productos (int $id): BinaryFileResponse
    {
        $datos = $this->em->getRepository(ProductoFoto::class)->findOneBy(['id'=>$id]);

        if(empty($datos)){
            return $this->json([
                'estado'=>'error',
                'mensaje'=>'foto-producto no encontrado'
            ]);
        }

        

        //te indica el directorio donde estas posicionado
        $ruta = getcwd();
        return $this->file("{$ruta}/uploads/fotos/{$datos->getFoto()}");

        
    }
}
