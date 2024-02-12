<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Cancion;
use App\Entity\Idioma;
use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class AlbumController extends AbstractController
{
    public function albums(Request $request, SerializerInterface $serializer)
    {
        if ($request->isMethod('GET')) {
            $albums = $this->getDoctrine()
                ->getRepository(Album::class)
                ->findAll();

            $albums = $serializer->serialize(
                $albums,
                'json',
                ['groups' => ['album', "artista_for_album"]]
            );

            return new Response($albums);
        }

        return new JsonResponse(['msg' => $request->getMethod() . ' not allowed']);
    }

    public function album(Request $request, SerializerInterface $serializer)
    {
        $id = $request->get('id');

        if ($request->isMethod('GET')) {
            $album = $this->getDoctrine()
                ->getRepository(Album::class)
                ->findOneBy(['id' => $id]);

            $album = $serializer->serialize(
                $album,
                'json',
                ['groups' => ['album', "artista_for_album"]]
            );

            return new Response($album);
        }

        return new JsonResponse(['msg' => $request->getMethod() . ' not allowed']);
    }

    public function albumCanciones(Request $request, SerializerInterface $serializer)
    {
        $id = $request->get('album_id');

        if ($request->isMethod('GET')) {
            $album = $this->getDoctrine()
                ->getRepository(Album::class)
                ->findOneBy(['id' => $id]);

            $canciones = $this->getDoctrine()
                ->getRepository(Cancion::class)
                ->findBy(['album' => $album]);

            $canciones = $serializer->serialize(
                $canciones,
                'json',
                ['groups' => ['cancion_for_album']]
            );

            return new Response($canciones);
        }

        return new JsonResponse(['msg' => $request->getMethod() . ' not allowed']);
    }

    public function albumsUsuario(Request $request, SerializerInterface $serializer)
    {
        $id = $request->get('usuario_id');

        if ($request->isMethod('GET')) {
            $albums = $this->getDoctrine()
                ->getRepository(Usuario::class)
                ->findOneBy(['id' => $id]);

            $albums = $serializer->serialize(
                $albums,
                'json',
                ['groups' => ['album_for_usuario', 'album_for_user', 'artista_for_album', 'artista_for_user']]
            );

            return new Response($albums);
        }

        return new JsonResponse(['msg' => $request->getMethod() . ' not allowed']);
    }
}