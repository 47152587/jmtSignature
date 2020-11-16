<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Usuario;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class JuicioController extends AbstractController
{
    /**
     * @Route("/juicio", name="juicio")
     */
    public function index(SerializerInterface $serializer): Response
    {
        
        $defaultTextA = "Demandados";
        $defaultTextB = "Demandantes";
        $arrUsers = $this->getDoctrine()->getRepository(Usuario::class)->findByTipoField(0);
        $existUser = count($arrUsers);
        $jsonContentA = "{}";
        if ($existUser > 0)
        {
            $jsonContentA = $serializer->serialize($arrUsers, 'json');
        }

        $arrUsers = $this->getDoctrine()->getRepository(Usuario::class)->findByTipoField(1);
        $existUser = count($arrUsers);
        $jsonContentB = "{}";
        if ($existUser > 0)
        {
            $jsonContentB = $serializer->serialize($arrUsers, 'json');
        }

        return $this->render('views/juicio.html.twig', [
            'defaultTextA' => $defaultTextA,
            'defaultTextB' => $defaultTextB,
            'jsonContentA' => $jsonContentA,
            'jsonContentB' => $jsonContentB
        ]);
    }

    /**
    * @Route("/addUser/{name}/{dni}", name="addUser", methods={"GET","POST"})
    */ 
    public function createUser($name, $dni, $tipo): Response
    {

        
        $arrUsers = $this->getDoctrine()->getRepository(Usuario::class)->findByDniField($dni);
        $existUser = count($arrUsers);

        if($existUser == 0)
        {
            $entityManager = $this->getDoctrine()->getManager();

            $usuario = new Usuario();
            $usuario->setNombre($name);
            $usuario->setDni($dni);
            $usuario->setTipo($tipo);

            // tell Doctrine you want to (eventually) save the Usuario (no queries yet)
            $entityManager->persist($usuario);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            return new JsonResponse([
                'status' => 'OK',
                'create' => 1,
                'user' => [
                    'name' => $usuario->getNombre(),
                    'dni' => $usuario>getDni()
                ]
            ], 200);

        }
        else
        {
                return new JsonResponse([
                    'status' => 'OK',
                    'create' => 0,
                    'user' => [
                        'name' => $arrUsers[0]->getNombre(),
                        'dni' => $arrUsers[0]->getDni()
                    ]
                ], 200);
        }
    }

}
