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
use function PHPUnit\Framework\isEmpty;

class ConfiguracionController extends AbstractController
{

    public function configuracionUsuario(Request $request, SerializerInterface $serializer)
    {
        $idUsuario = $request->get('usuario_id');

        $usuario = $this->getDoctrine()
            ->getRepository(Usuario::class)
            ->findOneBy(['id' => $idUsuario]);

        $configuracion = $this->getDoctrine()
            ->getRepository(Configuracion::class)
            ->findOneBy(['usuario' => $usuario]);

        if ($request->isMethod('GET')) {
            $configuracion = $serializer->serialize(
                $configuracion,
                'json',
                ['groups' => ['configuracion', "usuario_for_configuracion", "calidad_for_configuracion", "idioma_for_configuracion", "tipoDescarga_for_configuracion"]]
            );

            return new Response($configuracion);
        }

        if ($request->isMethod('PUT')) {

            if (!empty($configuracion)) {

                $bodyData = $request->getContent();
                $configuracionNueva = json_decode($bodyData, true);

                if (!empty($configuracionNueva['autoplay']))
                    $configuracion->setAutoplay($configuracionNueva['autoplay']);

                if (!empty($configuracionNueva['ajuste']))
                    $configuracion->setAutoplay($configuracionNueva['ajuste']);

                if (!empty($configuracionNueva['normalizacion']))
                    $configuracion->setAutoplay($configuracionNueva['normalizacion']);

                if (!empty($configuracionNueva['idioma_id'])) {
                    $idioma = $this->getDoctrine()->getRepository(Idioma::class)->findOneBy(['id' => $configuracionNueva['idioma_id']]);
                    $configuracion->setIdioma($idioma);
                }

                if (!empty($configuracionNueva['calidad_id'])) {
                    $calidad = $this->getDoctrine()->getRepository(Calidad::class)->findOneBy(['id' => $configuracionNueva['calidad_id']]);
                    $configuracion->setCalidad($calidad);
                }

                if (!empty($configuracionNueva['tipo_descarga_id'])) {
                    $tipoDescarga = $this->getDoctrine()->getRepository(TipoDescarga::class)->findOneBy(['id' => $configuracionNueva['tipo_descarga_id']]);
                    $configuracion->setTipoDescarga($tipoDescarga);
                }

                $this->getDoctrine()->getManager()->persist($configuracion);
                $this->getDoctrine()->getManager()->flush();

                $configuracion = $serializer->serialize(
                    $configuracion,
                    'json',
                    ['groups' => ['configuracion', "usuario_for_configuracion", "cancion_for_user", "calidad_for_configuracion", "idioma_for_configuracion", "tipoDescarga_for_configuracion"]]
                );

                return new Response($configuracion);
                }

            return new JsonResponse(['msg' => 'User not found', 404]);
        }

        return new JsonResponse(['msg' => $request->getMethod() . ' not allowed']);
    }
}
