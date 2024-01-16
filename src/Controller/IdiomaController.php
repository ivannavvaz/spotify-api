<?php

namespace App\Controller;

use App\Entity\Idioma;
use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class IdiomaController extends AbstractController
{
    public function idiomas(Request $request, SerializerInterface $serializer)
    {
        if ($request->isMethod('GET')) {
            $idiomas = $this->getDoctrine()
                ->getRepository(Idioma::class)
                ->findAll();

            $idiomas = $serializer->serialize($idiomas,
                'json',
                ['groups' => ['idioma']]
            );

            return new Response($idiomas);
        }

        return new JsonResponse(['msg' => $request->getMethod() . ' not allowed']);
    }
}