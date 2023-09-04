<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Entity\Producto;
use App\Entity\Categoria;

class DoctrineProductoController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }


    #[Route('/api/v1/doctrine/producto', methods:['GET'])]
    public function listar_productos(): JsonResponse
    {
        $productos = $this->em->getRepository(Producto::class)->findBy(array(),array('id'=>'DESC'));
        return $this->json($productos);
    }

    #[Route('/api/v1/doctrine/producto/{id}', methods:['GET'])]
    public function buscar_producto(int $id): JsonResponse
    {
        $productos = $this->em->getRepository(Producto::class)->find($id);

        if(empty($producto)){
            return $this->json([
                'estado'=>'error',
                'mensaje'=>'producto no encontrado'
            ]);
        }
        return $this->json([$productos]);
    }

    #[Route('/api/v1/doctrine/producto', methods:['POST'])]
    public function crear_producto(Request $request, SluggerInterface $slugger): JsonResponse
    {
        $datos = json_decode($request->getContent(), true);

        if(empty($datos['categoria_id']) || empty($datos['nombre']) || 
        empty($datos['precio']) || empty($datos['stock']) || 
        empty($datos['descripcion']))
        {
            return $this->json([
                'estado'=>'error',
                'mensaje'=>'faltan parámetros'
            ]);
        }

        $categoria = $this->em->getRepository(Categoria::class)->find($datos['categoria_id']);


        if(empty($categoria)){
            return $this->json([
                'estado'=>'error',
                'mensaje'=>'no existe la categoria'
            ]);
        }else{
            $producto = new Producto();
            //se debe colocar una entidad de tipo Categoria
            $producto->setCategoria($categoria);
            $producto->setNombre($datos['nombre']);
            $producto->setSlug($slugger->slug(strtolower($datos['nombre'])));
            $producto->setPrecio($datos['precio']);
            $producto->setStock($datos['stock']);
            $producto->setDescripcion($datos['descripcion']);
            $this->em->persist($producto);
            $this->em->flush();
            return $this->json([
                'estado'=>'ok',
                'mensaje'=>'producto creado'
            ]);

        }
    }

    #[Route('/api/v1/doctrine/producto/{id}', methods:['PUT'])]
    public function modificar_producto(int $id, Request $request, SluggerInterface $slugger): JsonResponse
    {

        $datos = json_decode($request->getContent(), true);
        if(empty($datos['categoria_id']) || empty($datos['nombre']) || 
        empty($datos['precio']) || empty($datos['stock']) || 
        empty($datos['descripcion']))
        {
            return $this->json([
                'estado'=>'error',
                'mensaje'=>'faltan parámetros'
            ]);
        }

        $categoria = $this->em->getRepository(Categoria::class)->find($datos['categoria_id']);
        if(empty($categoria)){
            return $this->json([
                'estado'=>'error',
                'mensaje'=>'no existe la categoria'
            ]);
        }

        $producto = $this->em->getRepository(Producto::class)->find($id);
        if(empty($producto)){
            return $this->json([
                'estado'=>'error',
                'mensaje'=>'no existe el producto'
            ]);
        }else{
            $producto->setCategoria($categoria);
            $producto->setNombre($datos['nombre']);
            $producto->setSlug($slugger->slug(strtolower($datos['nombre'])));
            $producto->setPrecio($datos['precio']);
            $producto->setStock($datos['stock']);
            $producto->setDescripcion($datos['descripcion']);
            $this->em->persist($producto);
            $this->em->flush();
            return $this->json([
                'estado'=>'ok',
                'mensaje'=>'producto modificado'
            ]);
        }


    }

    #[Route('/api/v1/doctrine/producto/{id}', methods:['DELETE'])]
    public function eliminar_producto(int $id): JsonResponse
    {
        $producto = $this->em->getRepository(Producto::class)->find($id);
        if(isset($producto)){
            $this->em->remove($producto);
            $this->em->flush();
            return $this->json([
                'estado'=>'ok',
                'mensaje'=>'producto eliminado'
            ]);

        }else{
            return $this->json([
                'estado'=>'error',
                'mensaje'=>'producto no encontrado'
            ]);
        }
        
    }
}
