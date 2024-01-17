<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Artista;
use Doctrine\DBAL\Driver\AbstractDB2Driver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class ArtistaController extends AbstractController
{
    public function artistas(Request $request, SerializerInterface $serializer)
    {
        if ($request->isMethod('GET')) {
            $artistas = $this->getDoctrine()
                ->getRepository(Artista::class)
                ->findAll();

            $artistas = $serializer->serialize(
                $artistas,
                'json',
                ['groups' => ['artista']]
            );

            return new Response($artistas);
        }

        return new JsonResponse(['msg' => $request->getMethod() . ' not allowed']);
    }

    public function artistaAlbums(Request $request, SerializerInterface $serializer)
    {
        $id = $request->get('artista_id');

        if ($request->isMethod('GET')) {
            $artista = $this->getDoctrine()
                ->getRepository(Artista::class)
                ->findOneBy(['id' => $id]);

            $albums = $this->getDoctrine()
                ->getRepository(Album::class)
                ->findBy(['artista' => $artista]);

            $albums = $serializer->serialize(
                $albums,
                'json',
                ['groups' => ['album_for_artista']]
            );

            return new Response($albums);
        }

        return new JsonResponse(['msg' => $request->getMethod() . ' not allowed']);
    }

    public function artistaAlbum(Request $request, SerializerInterface $serializer)
    {
        $idArtista = $request->get('artista_id');
        $idAlbum = $request->get('album_id');

        if ($request->isMethod('GET')) {
            $artista = $this->getDoctrine()
                ->getRepository(Artista::class)
                ->findOneBy(['id' => $idArtista]);

            $albums = $this->getDoctrine()
                ->getRepository(Album::class)
                ->findOneBy(['artista' => $artista, 'id' => $idAlbum]);

            $albums = $serializer->serialize(
                $albums,
                'json',
                ['groups' => ['album_for_artista']]
            );

            return new Response($albums);
        }

        return new JsonResponse(['msg' => $request->getMethod() . ' not allowed']);
    }
}