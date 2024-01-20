<?php

namespace App\Controller;

use App\Entity\Activa;
use App\Entity\Eliminada;
use App\Entity\Favoritas;
use App\Entity\Playlist;
use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use \DateTime;

class PlaylistController extends AbstractController
{
    public function playlists(Request $request, SerializerInterface $serializer)
    {
        if ($request->isMethod("GET")) {
            
            #Playlist creadas activas
            $playlistActivas = $this->getDoctrine()
                ->getRepository(Activa::class)
                ->findAll();

            #Playlists favoritas
            $playlistFavs = $this->getDoctrine()
            ->getRepository(Favoritas::class)
            ->findAll();

            #MIX
            $playlists = [$playlistFavs, $playlistActivas];
            

            $playlists = $serializer->serialize(
                $playlists, 
                'json', 
                ['groups' => ['favoritas', 'activa', 'playlist', 'usuario_for_playlist']]);


            //return new JsonResponse($planets, 200, [], true);
            return new Response($playlists);
        }

        if ($request->isMethod("POST")) {

            $titulo = $request->get("titulo");
            $nCanciones = $request->get("numero_canciones");
            $usrId = $request->get("usuario_id");

            $usuario = $this->getDoctrine()
            ->getRepository(Usuario::class)
            ->findOneBy(['id' => $usrId]);

            $playlist = new Playlist();

            $playlist->setTitulo($titulo);
            $playlist->setNumeroCanciones($nCanciones);
            $playlist->setFechaCreacion(new DateTime('now'));
            $playlist->setUsuario($usuario);

            $playlistActiva = new Activa();
            $playlistActiva->setEsCompartida(0);
            $playlistActiva->setPlaylist($playlist);

            $this->getDoctrine()->getManager()->persist($playlist);
            $this->getDoctrine()->getManager()->persist($playlistActiva);
            $this->getDoctrine()->getManager()->flush();

            $playlists = $serializer->serialize(
                $playlist, 
                'json', 
                ['groups' => 'playlist', 'usuario_for_playlist']);

                return new Response($playlists);
        }

        return new JsonResponse(['msg' => $request->getMethod() . ' not allowed']);
    }

    public function playlist(Request $request, SerializerInterface $serializer)
    {
        $id = $request->get('id');

        $playlist = null;

        $playlist = $this->getDoctrine()
        ->getRepository(Playlist::class)
        ->findOneBy(['id' => $id]);

        $activa = $this->getDoctrine()
        ->getRepository(Activa::class)
        ->findOneBy(['playlist' => $playlist]);

        $favorita = $this->getDoctrine()
        ->getRepository(Favoritas::class)
        ->findOneBy(['playlist' => $playlist]);

        if ($request->isMethod("GET")) {

            if (!is_null($activa) or !is_null($favorita)) {
                $playlist = $serializer->serialize(
                    $playlist, 
                    'json', [
                    'groups' => ['playlist', 'usuario_for_playlist']]);
    
                return new Response($playlist);
            }
            return new JsonResponse(['msg' => "Not found."], 404);
        }

        return new JsonResponse(['msg' => $request->getMethod() . ' not allowed']);
    }

    public function playlistsUsuario(Request $request, SerializerInterface $serializer)
    {
        $id = $request->get('usuario_id');

        $playlists = null;

        #PLAYLISTS QUE SIGUE
         /*
        $playlistsSigue = $this->getDoctrine()
        ->getRepository(Usuario::class)
        ->findOneBy(['id' => $id])
        ->getPlaylist();
        */

        #PLAYLISTS QUE HA CREADO
        
        $playlists_todas = $this->getDoctrine()
        ->getRepository(Playlist::class)
        ->findBy(['usuario' => $id]);

        $eliminadas_todo = $this->getDoctrine()
        ->getRepository(Eliminada::class)
        ->findAll();
        
        #MIX
        # $playlists = [$playlistsCrea, $playlistsSigue];

        if ($request->isMethod("GET")) {

            $eliminadas = array();

            foreach ($eliminadas_todo as $e) {
                #array_push($eliminadas, $e->getPlaylist());
                $eliminadas[] = $e->getPlaylist();
            }

            $playlists = array();

            foreach ($playlists_todas as $p) {
                if (!in_array($p, $eliminadas)) {
                    #array_push($playlist, $p);
                    $playlists[] = $p;
                }
            }

            $playlists = $serializer->serialize(
                $playlists, 
                'json', [
                'groups' => ['playlist', 'usuario_for_playlist']]);

            return new Response($playlists);
        }
        return new JsonResponse(['msg' => $request->getMethod() . ' not allowed']);
    }

    public function playlistUsuario(Request $request, SerializerInterface $serializer)
    {
        $usuarioId = $request->get('usuario_id');
        $playlistId = $request->get('playlist_id');

        $playlistsUsuario = $this->getDoctrine()
        ->getRepository(Playlist::class)
        ->findBy(['usuario' => $usuarioId]);

        $playlist = null;

        $playlist = $this->getDoctrine()
        ->getRepository(Playlist::class)
        ->findOneBy(['id' => $playlistId]);

        $activa = $this->getDoctrine()
        ->getRepository(Activa::class)
        ->findOneBy(['playlist' => $playlist]);

        $favorita = $this->getDoctrine()
        ->getRepository(Favoritas::class)
        ->findOneBy(['playlist' => $playlist]);

        if ($request->isMethod("GET")) {
            
            if (in_array($playlist, $playlistsUsuario) && !is_null($activa) && !is_null($favorita)){
                $playlist = $serializer->serialize(
                    $playlist, 
                    'json', [
                    'groups' => ['playlist', 'usuario_for_playlist']]);

                return new Response($playlist);
            }
            return new JsonResponse(['msg' => "Not found."], 404);
        }


        if ($request->isMethod("PUT")) {
            if (in_array($playlist, $playlistsUsuario) && !is_null($activa)){
                if (!empty($playlist)){

                    $titulo = $request->get("titulo");
                    $nCanciones = $request->get("numero_canciones");
                    
                    $playlist->setTitulo($titulo);
                    $playlist->setNumeroCanciones($nCanciones);

        
                    $this->getDoctrine()->getManager()->persist($playlist);
                    $this->getDoctrine()->getManager()->flush();
        
                    $playlist = $serializer->serialize(
                        $playlist, 
                        'json', [
                        'groups' => ['activa', 'playlist', 'usuario_for_playlist']]);
        
                    return new Response($playlist);
                }
            }
            return new JsonResponse(['msg' => "Not found."], 404);
        }

        if ($request->isMethod("DELETE")) {

            if (in_array($playlist, $playlistsUsuario) && !is_null($activa)){
                $deletedPlaylist = new Eliminada();
                $deletedPlaylist->setFechaEliminacion(new DateTime('now'));
                $deletedPlaylist->setPlaylist($playlist);

                

                $this->getDoctrine()->getManager()->remove($activa);
                $this->getDoctrine()->getManager()->persist($deletedPlaylist);
                $this->getDoctrine()->getManager()->flush();

                

                $deletedPlaylist = $serializer->serialize($playlist, 'json', ['groups' => 'playlist', 'usuario_for_playlist']);
                return new Response($deletedPlaylist);
            }
            return new JsonResponse(['msg' => "Not found."], 404);
        }        
            
        return new JsonResponse(['msg' => $request->getMethod() . ' not allowed']);
    }
        
}