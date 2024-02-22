<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\AnyadeCancionPlaylist;
use App\Entity\Cancion;
use App\Entity\Playlist;
use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class CancionController extends AbstractController
{
    public function canciones(Request $request, SerializerInterface $serializer)
    {
        if ($request->isMethod('GET')) {
            $canciones = $this->getDoctrine()
                ->getRepository(Cancion::class)
                ->findAll();

            $canciones = $serializer->serialize(
                $canciones,
                'json',
                ['groups' => ['cancion', "album_for_cancion", "artista_for_cancion"]]
            );

            return new Response($canciones);
        }

        return new JsonResponse(['msg' => $request->getMethod() . ' not allowed']);
    }

    public function cancion(Request $request, SerializerInterface $serializer)
    {
        $id = $request->get('id');

        if ($request->isMethod('GET')) {
            $cancion = $this->getDoctrine()
                ->getRepository(Cancion::class)
                ->findOneBy(['id' => $id]);

            $cancion = $serializer->serialize(
                $cancion,
                'json',
                ['groups' => ['cancion', "album_for_cancion", "artista_for_cancion"]]
            );

            return new Response($cancion);
        }

        return new JsonResponse(['msg' => $request->getMethod() . ' not allowed']);
    }

    public function cancionesPlaylist(Request $request, SerializerInterface $serializer)
    {
        $id = $request->get('playlist_id');

        if ($request->isMethod('GET')) {
            $playlist = $this->getDoctrine()
                ->getRepository(Playlist::class)
                ->findOneBy(['id' => $id]);

            $anyadeCancionPlaylist = $this->getDoctrine()
                ->getRepository(AnyadeCancionPlaylist::class)
                ->findBy(['playlist' => $playlist]);


            $res = array();

            foreach ($anyadeCancionPlaylist as $cancionPlaylist)
            {

                $cancion = $this->getDoctrine()
                ->getRepository(Cancion::class)
                ->findOneBy(['id' => $cancionPlaylist->getCancion()]);

                //$clonada = $cancion[0];
                
                $res[] = $cancion;
            }
                

            $res = $serializer->serialize(
                $res,
                'json',
                ['groups' => ['canciones_de_playlist', 'cancion', 'album_for_cancion', "artista_for_cancion"]]
            );

            return new Response($res);
        }

        return new JsonResponse(['msg' => $request->getMethod() . ' not allowed']);
    }

    public function cancionPlaylist(Request $request, SerializerInterface $serializer)
    {
        $idPlaylist = $request->get('playlist_id');
        $idCancion = $request->get('cancion_id');
        $idUsuario = $request->get('usuario_id');

        $playlist = $this->getDoctrine()
            ->getRepository(Playlist::class)
            ->findOneBy(['id' => $idPlaylist]);

        $cancion = $this->getDoctrine()
            ->getRepository(Cancion::class)
            ->findOneBy(['id' => $idCancion]);

        $usuario = $this->getDoctrine()
            ->getRepository(Usuario::class)
            ->findOneBy(['id' => $idUsuario]);

        if ($request->isMethod('POST')) {

            $anyadeCancionPlaylist = new AnyadeCancionPlaylist(new \DateTime(), $usuario, $playlist, $cancion);

            $this->getDoctrine()->getManager()->persist($anyadeCancionPlaylist);
            $this->getDoctrine()->getManager()->flush();

            $anyadeCancionPlaylist = $serializer->serialize(
                $anyadeCancionPlaylist,
                'json',
                ['groups' => ['canciones_de_playlist', 'cancion', 'album_for_cancion', "artista_for_cancion"]]
            );

            return new Response($anyadeCancionPlaylist);
        }

        if ($request->isMethod('DELETE')) {

            $anyadeCancionPlaylist = $this->getDoctrine()
                ->getRepository(AnyadeCancionPlaylist::class)
                ->findOneBy(['playlist' => $playlist, 'cancion' => $cancion]);

            $deletedCancion = clone $anyadeCancionPlaylist;
            $this->getDoctrine()->getManager()->remove($anyadeCancionPlaylist);
            $this->getDoctrine()->getManager()->flush();

            $deletedCancion = $serializer->serialize(
                $deletedCancion,
                'json',
                ['groups' => ['canciones_de_playlist', 'cancion', 'album_for_cancion', "artista_for_cancion"]]
            );

            return new Response($deletedCancion);

        }

        return new JsonResponse(['msg' => $request->getMethod() . ' not allowed']);
    }
}