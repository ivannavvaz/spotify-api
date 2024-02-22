<?php

namespace App\Controller;

use App\Entity\Capitulo;
use App\Entity\Podcast;
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
            ->getRepository(Podcast::class)
            ->findOneBy(['id' => $id]);
            
            #capitulos
            $capitulos = $this->getDoctrine()
            ->getRepository(Capitulo::class)
            ->findBy(['podcast' => $podcast]);

            $capitulos = $serializer->serialize(
                $capitulos, 
                'json',
                ['groups' => ['capitulo', "podcast", 'usuario_for_podcast']]);

            return new Response($capitulos);
        }
        return new JsonResponse(['msg' => $request->getMethod() . ' not allowed']);
    }

    public function capituloPodcast(Request $request, SerializerInterface $serializer)
    {
        if ($request->isMethod("GET")) {
            
            $podcast_id = $request->get('podcast_id');
            $capitulo_id = $request->get('capitulo_id');

            $podcast = $this->getDoctrine()
            ->getRepository(Podcast::class)
            ->findOneBy(['id' => $podcast_id]);
            
            #capitulo
            $capitulo = $this->getDoctrine()
            ->getRepository(Capitulo::class)
            ->findOneBy(['id' => $capitulo_id]);

            if ($capitulo->getPodcast() == $podcast) {
                $capitulo = $serializer->serialize(
                    $capitulo, 
                    'json', 
                    ['groups' => 'capitulo']);
    
                return new Response($capitulo);
            }

            return new JsonResponse(['msg' => "Not found."], 404);
        }
        return new JsonResponse(['msg' => $request->getMethod() . ' not allowed']);
    }
}