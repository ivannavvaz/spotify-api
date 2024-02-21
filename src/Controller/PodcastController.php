<?php

namespace App\Controller;

use App\Entity\Podcast;
use App\Entity\Usuario;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class PodcastController extends AbstractController
{


    public function podcasts(Request $request, SerializerInterface $serializer)
    {

        if ($request->isMethod('GET')) {
            $podcasts = $this->getDoctrine()
                ->getRepository(Podcast::class)
                ->findAll();

            $podcasts = $serializer->serialize($podcasts, 'json', ['groups' => ['podcasts', 'usuario_for_podcast']]);

            return new Response($podcasts);
        }

        return new JsonResponse(['msg' => $request->getMethod() . ' not allowed']);

    }


    public function podcast(Request $request, SerializerInterface $serializer)
    {

        if ($request->isMethod('GET')) {
            $id = $request->get('id');

            $podcast = $this->getDoctrine()
                ->getRepository(Podcast::class)
                ->findOneBy(['id' => $id]);

            $podcast = $serializer->serialize($podcast, 'json', ['groups' => ['podcast', 'usuario_for_podcast']]);

            return new Response($podcast);
        }

        return new JsonResponse(['msg' => $request->getMethod() . ' not allowed']);

    }

    public function usuarioPodcasts(Request $request, SerializerInterface $serializer)
    {

        if ($request->isMethod('GET')) {
            $id = $request->get('id');

            $usuario = $this->getDoctrine()
                ->getRepository(Usuario::class)
                ->findOneBy(['id' => $id]);

            $podcasts = $usuario->getPodcast();

            $podcasts = $serializer->serialize($podcasts, 'json', ['groups' => ['podcast_for_usuario']]);

            return new Response($podcasts);

        }

        return new JsonResponse(['msg' => $request->getMethod() . ' not allowed']);

    }

    public function usuarioPodcast(Request $request, SerializerInterface $serializer)
    {
        if ($request->isMethod('POST')) {
            $id_usuario = $request->get('usuario_id');
            $id_podcast = $request->get('podcast_id');

            $usuario = $this->getDoctrine()
                ->getRepository(Usuario::class)
                ->findOneBy(['id' => $id_usuario]);

            $podcast = $this->getDoctrine()
                ->getRepository(Podcast::class)
                ->findOneBy(['id' => $id_podcast]);

            $podcasts_followed_by_user = $usuario->getPodcast();

            if ($podcasts_followed_by_user->contains($podcast)) {
                return new JsonResponse(['msg' => 'Podcast already followed by user']);
            } else {
                $podcasts_followed_by_user->add($podcast);
            }

            $usuario->setPodcast($podcasts_followed_by_user);

            $this->getDoctrine()
                ->getManager()
                ->persist($usuario);

            $this->getDoctrine()
                ->getManager()
                ->flush();

            $podcast = $serializer->serialize($usuario, 'json', ['groups' => ['usuario']]);

            return new Response($podcast);

        }

        if ($request->isMethod('DELETE')) {
            $id_usuario = $request->get('usuario_id');
            $id_podcast = $request->get('podcast_id');

            $usuario = $this->getDoctrine()
                ->getRepository(Usuario::class)
                ->findOneBy(['id' => $id_usuario]);

            $podcast = $this->getDoctrine()
                ->getRepository(Podcast::class)
                ->findOneBy(['id' => $id_podcast]);

            $podcasts_followed_by_user = $usuario->getPodcast();

            if ($podcasts_followed_by_user->contains($podcast)) {
                $podcasts_followed_by_user->removeElement($podcast);
            } else {
                return new JsonResponse(['msg' => 'Podcast not followed by user']);
            }

            $usuario->setPodcast($podcasts_followed_by_user);

            $this->getDoctrine()
                ->getManager()
                ->persist($usuario);

            $this->getDoctrine()
                ->getManager()
                ->flush();

            $podcast = $serializer->serialize($usuario, 'json', ['groups' => ['usuario']]);

            return new Response($podcast);

        }

        return new JsonResponse(['msg' => $request->getMethod() . ' not allowed']);

    }

}