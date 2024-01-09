<?php

namespace App\Controller;

use App\Entity\Configuracion;
use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class ConfiguracionController extends AbstractController
{

    public function configuracionUsuario(Request $request, SerializerInterface $serializer)
    {
        $id = $request->get('usuario_id');

        $usuario = $this->getDoctrine()
            ->getRepository(Usuario::class)
            ->findOneBy(['id' => $id]);

        $configuracion = $this->getDoctrine()
            ->getRepository(Configuracion::class)
            ->findOneBy(['usuario' => $usuario]);

        if ($request->isMethod('GET')) {
            $configuracion = $serializer->serialize(
                $configuracion,
                'json',
                ['groups' => ['configuracion', "usuario_for_configuracion", "cancion_for_user", "calidad_for_configuracion", "idioma_for_configuracion", "tipoDescarga_for_configuracion"]]
            );

            return new Response($configuracion);
        }

        if ($request->isMethod('PUT')) {
            if (!empty($configuracion)) {

                $bodyData = $request->getContent();
                $configuracion = $serializer->deserialize(
                    $bodyData,
                    Configuracion::class,
                    'json',
                    ['object_to_populate' => $configuracion]
                );

                $this->getDoctrine()->getManager()->persist($configuracion);
                $this->getDoctrine()->getManager()->flush();

                $configuracion = $serializer->serialize(
                    $configuracion,
                    'json',
                    ['groups' => ['configuracion', "usuario_for_configuracion", "cancion_for_user", "calidad_for_configuracion", "idioma_for_configuracion", "tipoDescarga_for_configuracion"]]
                );

                return new Response($configuracion);
            }

            return new JsonResponse(['msg' => 'Configuration not found', 404]);
        }

        return new JsonResponse(['msg' => $request->getMethod() . ' not allowed']);
    }
}
