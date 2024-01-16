<?php

namespace App\Controller;

use App\Entity\Calidad;
use App\Entity\Idioma;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class CalidadController extends AbstractController
{
    public function calidades(Request $request, SerializerInterface $serializer)
    {
        if ($request->isMethod('GET')) {
            $calidades = $this->getDoctrine()
                ->getRepository(Calidad::class)
                ->findAll();

            $calidades = $serializer->serialize($calidades,
                'json',
                ['groups' => ['calidad']]
            );

            return new Response($calidades);
        }

        return new JsonResponse(['msg' => $request->getMethod() . ' not allowed']);
    }
}