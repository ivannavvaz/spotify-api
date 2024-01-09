<?php

namespace App\Controller;

use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class UsuarioController extends AbstractController
{
    public function usuarios(Request $request, SerializerInterface $serializer)
    {
        if ($request->isMethod('GET')) {
            $usuario = $this->getDoctrine()
                ->getRepository(Usuario::class)
                ->findAll();

            $usuario = $serializer->serialize($usuario, 'json', ['groups' => ['usuario' ,'podcast_for_user', 'album_for_user', 'artista_for_user', 'playlist_for_user']]);

            return new Response($usuario);
        }

        if ($request->isMethod('POST')) {

            $data = $request->getContent();
            $usuario = $serializer->deserialize($data, Usuario::class, 'json');

            $this->getDoctrine()
                ->getManager()
                ->persist($usuario);

            $this->getDoctrine()
                ->getManager()
                ->flush();

            $usuario = $serializer->serialize($usuario, 'json', ['groups' => ['usuario']]);

            return new Response($usuario);

        }

        return new JsonResponse(['msg' => $request->getMethod() . ' not allowed']);

    }
}
