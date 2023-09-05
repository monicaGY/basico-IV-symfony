<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Usuario;

class AccesoController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }


    #[Route('/api/v1/acceso/registro', methods:['POST'])]
    public function registro(Request $request,UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $datos = json_decode($request->getContent(), true);

        if(empty($datos['nombre'])){
            return $this->json([
                'estado'=>'error',
                'mensaje'=>'El campo nombre es obligatorio'
            ],200);
        }

        if(empty($datos['correo'])){
            return $this->json([
                'estado'=>'error',
                'mensaje'=>'El campo correo es obligatorio'
            ],200);
        }

        if(empty($datos['password'])){
            return $this->json([
                'estado'=>'error',
                'mensaje'=>'El campo password es obligatorio'
            ],200);
        }

        $existe = $this->em->getRepository(Usuario::class)->findOneBy(['correo'=>$datos['correo']]);

        if($existe){
            return $this->json([
                'estado'=>'error',
                'mensaje'=>"El correo {$datos['correo']} ya existe"
            ],200);
        }

        $entity = new Usuario();
        $entity->setNombre($datos['nombre']);
        $entity->setCorreo($datos['correo']);
        $entity->setPassword($passwordHasher->hashPassword(
            $entity,$datos['password']
        ));
        $entity->setRoles(['ROLE_USER']);
        $this->em->persist($entity);
        $this->em->flush();

        return $this->json([
            'estado'=>'ok',
            'mensaje'=>"El registro se completo con éxito"
        ],Response::HTTP_CREATED);

    }
}
