<?php

namespace App\Controller;

use App\Entity\Calidad;
use App\Entity\Configuracion;
use App\Entity\Idioma;
use App\Entity\TipoDescarga;
use App\Entity\Usuario;
use Psr\Log\LoggerInterface;
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

    public function usuarioByEmail(Request $request, SerializerInterface $serializer)
    {
        // IMPORTANTE: para que un metodo con Body funcione, el request debe ser POST de lo
        // contrario a pesar de que esde postman esto sea valido, desde retrofit saltaria
        // un error que incapacitaria la request
        //
        // A tener en cuenta en todos los metodos con body


        if ($request->isMethod('POST')) {
            $bodyData = $request->getContent();
            $bodyData = json_decode($bodyData, true);

            $email = $bodyData['email'];
            $password = $bodyData['password'];

            $usuario = $this->getDoctrine()
                ->getRepository(Usuario::class)
                ->findOneBy(['email' => $email, 'password' => $password]);

            if (is_null($usuario)) {
                return new JsonResponse(['error' => 'Unauthorized'], 401);
            }

            $usuario = $serializer->serialize($usuario, 'json', ['groups' => ['usuarios']]);

            
            return new Response($usuario);
        }
    }

    public function usuarioByUsername(Request $request, SerializerInterface $serializer)
    {
        if ($request->isMethod('POST')) {
            $bodyData = $request->getContent();

            $bodyData = json_decode($bodyData, true);

            $username = $bodyData['username'];
            $password = $bodyData['password'];

            $usuario = $this->getDoctrine()
                ->getRepository(Usuario::class)
                ->findOneBy(['username' => $username, 'password' => $password]);


            if (is_null($usuario) ) {
                return new JsonResponse(['error' => 'Unauthorized'], 401);
            }
            

            $usuario = $serializer->serialize($usuario, 'json', ['groups' => ['usuarios']]);

            
            return new Response($usuario);
        }
    }

    public function usuarioValidar(Request $request, SerializerInterface $serializer)
    {
        if ($request->isMethod('POST')) {
            $bodyData = $request->getContent();

            $bodyData = json_decode($bodyData, true);

            $username = $bodyData['username'];
            $email = $bodyData['email'];

            $usuarioUsername = $this->getDoctrine()
                ->getRepository(Usuario::class)
                ->findOneBy(['username' => $username]);

            $usuarioEmail = $this->getDoctrine()
                ->getRepository(Usuario::class)
                ->findOneBy(['email' => $email]);

            if (is_null($usuarioEmail) && is_null($usuarioUsername)) {
                return new JsonResponse(['response' => 'Authorized']);
            }
            return new JsonResponse(['response' => 'Unauthorized']);

        }
    }

}
