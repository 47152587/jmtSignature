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
        $option1 = "option1";
        $option2 = "option2";
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
            'jsonContentB' => $jsonContentB,
            'option1' => $option1,
            'option2' => $option2
        ]);
    }
//    checkVs
/**
    * @Route("/checkVs/{a1}/{b1}/{c1}/{ab1}/{bb1}/{cb1}", name="addUser", methods={"GET","POST"})
    */ 
    public function checkVs($a1, $b1, $c1, $ab1, $bb1, $cb1, $nombre1, $nombre2): Response
    {
        $total_demandado = ($a1 * 5) + ($b1 * 2) + ($c1 * 1);
        $total_demandate = ($ab1 * 5) + ($bb1 * 2) + ($cb1 * 1);
        
        if ($total_demandado > $total_demandate)
        {
           return new JsonResponse([
                'status' => 'OK',
                'msg' => 'El ganador es el demandado con ' . $total_demandado
            ], 200);
        }

       if ($total_demandado == $total_demandate)
        {
            return new JsonResponse([
                'status' => 'OK',
                'msg' => 'Las dos partes son iguales ' . $total_demandado
            ], 200);
        } 

        if ($total_demandado < $total_demandate)
        {
            return new JsonResponse([
                'status' => 'OK',
                'msg' => 'El ganador es el demandante con ' . $total_demandate
            ], 200);
        } 
        
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
