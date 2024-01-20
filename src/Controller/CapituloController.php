<?php

namespace App\Controller;

use App\Entity\Capitulo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class CapituloController extends AbstractController
{
    public function capitulosPodcast(Request $request, SerializerInterface $serializer)
    {
        if ($request->isMethod("GET")) {
            
            $id = $request->get('podcast_id');

            $podcast = $this->getDoctrine()
            ->getRepository(Capitulo::class)
            ->findOneBy(['id' => $id]);
            
            #capitulos
            $capitulos = $this->getDoctrine()
            ->getRepository(Capitulo::class)
            ->findBy(['podcast' => $podcast]);

            $capitulos = $serializer->serialize(
                $capitulos, 
                'json', 
                ['groups' => 'capitulo']);

            return new Response($capitulos);
        }
        return new JsonResponse(['msg' => $request->getMethod() . ' not allowed']);
    }

    public function capituloPodcast(Request $request, SerializerInterface $serializer)
    {
        if ($request->isMethod("GET")) {
            
            $podcast_id = $request->get('podcast_id');

            $podcast = $this->getDoctrine()
            ->getRepository(Capitulo::class)
            ->findOneBy(['id' => $podcast_id]);
            
            #capitulos
            $capitulo = $this->getDoctrine()
            ->getRepository(Capitulo::class)
            ->findOneBy(['podcast' => $podcast]);

            $capitulo = $serializer->serialize(
                $capitulo, 
                'json', 
                ['groups' => 'capitulo']);

            return new Response($capitulo);
        }
        return new JsonResponse(['msg' => $request->getMethod() . ' not allowed']);
    }
}