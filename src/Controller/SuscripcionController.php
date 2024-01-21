<?php

namespace App\Controller;

use App\Entity\Suscripcion;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class SuscripcionController extends AbstractController
{
    public function suscripcionesUsuario(Request $request, SerializerInterface $serializer)
    {
        if ($request->isMethod("GET")) {
            
            $usr = $request->get('usuario_id');

            $suscripcion= $this->getDoctrine()
            ->getRepository(Suscripcion::class)
            ->findBy(['premiumUsuario' => $usr]);

            $suscripcion = $serializer->serialize(
                $suscripcion, 
                'json', 
                ['groups' => 'suscripcion', 'premium_for_suscripcion']);

            return new Response($suscripcion);
        }

        return new JsonResponse(['msg' => $request->getMethod() . ' not allowed']);
    }

    public function suscripcionUsuario(Request $request, SerializerInterface $serializer)
    {
        if ($request->isMethod("GET")) {
            
            $usr_id = $request->get('usuario_id');
            $sus_id = $request->get('suscripcion_id');

            $suscripcion= $this->getDoctrine()
            ->getRepository(Suscripcion::class)
            ->findOneBy(['id' => $sus_id]);

            $suscripciones = $this->getDoctrine()
            ->getRepository(Suscripcion::class)
            ->findBy(['premiumUsuario' => $usr_id]);

            if (in_array($suscripcion, $suscripciones)) {
                $suscripcion = $serializer->serialize(
                    $suscripcion, 
                    'json', 
                    ['groups' => 'suscripcion', 'premium_for_suscripcion', 'usuario_for_playlist']);
    
                return new Response($suscripcion);
            }
            return new JsonResponse(['msg' => "Not found."], 404);
        }
        return new JsonResponse(['msg' => $request->getMethod() . ' not allowed']);
    }
}