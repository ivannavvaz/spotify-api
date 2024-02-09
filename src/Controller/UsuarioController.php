<?php

namespace App\Controller;

use App\Entity\Calidad;
use App\Entity\Configuracion;
use App\Entity\Idioma;
use App\Entity\TipoDescarga;
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

            $usuario = $serializer->serialize($usuario, 'json', ['groups' => ['usuarios']]);

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



            // crear configuracion basica para el usuario


            $configuracion = new Configuracion();
            $configuracion->setUsuario($usuario);
            $configuracion->setAutoplay(false);
            $configuracion->setAjuste(0);
            $configuracion->setNormalizacion(false);
            $configuracion->setIdioma($this->getDoctrine()->getRepository(Idioma::class)->findOneBy(['id' => 1]));
            $configuracion->setCalidad($this->getDoctrine()->getRepository(Calidad::class)->findOneBy(['id' => 1]));
            $configuracion->setTipoDescarga($this->getDoctrine()->getRepository(TipoDescarga::class)->findOneBy(['id' => 1]));

            $this->getDoctrine()
                ->getManager()
                ->persist($configuracion);

            $this->getDoctrine()
                ->getManager()
                ->flush();

            $usuario = $serializer->serialize($usuario, 'json', ['groups' => ['usuarios']]);

            return new Response($usuario);

        }

        return new JsonResponse(['msg' => $request->getMethod() . ' not allowed']);

    }

    public function usuario(Request $request, SerializerInterface $serializer)
    {

        if ($request->isMethod('GET')) {
            $id = $request->get('id');

            $usuario = $this->getDoctrine()
                ->getRepository(Usuario::class)
                ->findOneBy(['id' => $id]);



            if (is_null($usuario)) {
                return new JsonResponse(['error' => 'auth required'], 401);
            }
            

            $usuario = $serializer->serialize($usuario, 'json', ['groups' => ['usuarios']]);

            
            return new Response($usuario);
        }

        if ($request->isMethod('PUT')) {

            $id = $request->get('id');

            $usuario = $this->getDoctrine()
                ->getRepository(Usuario::class)
                ->findOneBy(['id' => $id]);

            if (!empty($usuario)) {
                $data = $request->getContent();
                $usuario = $serializer->deserialize(
                    $data,
                    Usuario::class,
                    'json',
                    ['object_to_populate' => $usuario]
                );

                $this->getDoctrine()
                    ->getManager()
                    ->persist($usuario);

                $this->getDoctrine()
                    ->getManager()
                    ->flush();

                $usuario = $serializer->serialize($usuario, 'json', ['groups' => ['usuarios']]);
                return new Response($usuario);
            }

            return new JsonResponse(['msg' => 'Usuario not found']);
        }

        return new JsonResponse(['msg' => $request->getMethod() . ' not allowed']);

    }

    public function usuarioEmail(Request $request, SerializerInterface $serializer)
    {
        if ($request->isMethod('GET')) {
            $email = $request->get('email');

            $usuario = $this->getDoctrine()
                ->getRepository(Usuario::class)
                ->findOneBy(['email' => $email]);

            if (is_null($usuario)) {
                return new JsonResponse(['error' => 'Not Found'], 404);
            }

            $usuario = $serializer->serialize($usuario, 'json', ['groups' => ['usuarios']]);

            
            return new Response($usuario);
        }
    }

    public function usuarioUsername(Request $request, SerializerInterface $serializer)
    {
        if ($request->isMethod('GET')) {
            $username = $request->get('username');

            $usuario = $this->getDoctrine()
                ->getRepository(Usuario::class)
                ->findOneBy(['username' => $username]);



            if (is_null($usuario)) {
                return new JsonResponse(['error' => 'Not Found'], 404);
            }
            

            $usuario = $serializer->serialize($usuario, 'json', ['groups' => ['usuarios']]);

            
            return new Response($usuario);
        }
    }

}
