<?php

namespace App\Controller\Transporte\Api;

use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteNovedad;
use App\Entity\Transporte\TteNovedadTipo;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ApiGuiaController extends FOSRestController
{

    /**
     * @param Request $request
     * @param int $codigoGuia
     * @param int $documentoCliente
     * @return array
     * @Rest\Get("/transporte/api/app/guia/consulta/{codigoGuia}/{documentoCliente}")
     */
    public function consulta(Request $request, $codigoGuia = 0, $documentoCliente = 0)
    {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $arGuia = $this->getDoctrine()->getManager()->getRepository(TteGuia::class)->apiConsulta($codigoGuia, $documentoCliente);
        if ($arGuia) {
            return [
                'error' => false,
                'mensaje' => "Guia encontrada",
                'guias' => $arGuia,
            ];
        } else {
            return [
                'error' => true,
                'mensaje' => "No se encontraron guias",
                'guias' => null,
            ];
        }
    }

    /**
     * @param Request $request
     * @param $codigoGuia
     * @return JsonResponse
     * @Rest\Get("/transporte/api/app/guia/novedad/{codigoGuia}")
     */
    public function consultaNovedad(Request $request, $codigoGuia){
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $arNovedades = $this->getDoctrine()->getManager()->getRepository(TteNovedad::class)->apiConsulta($codigoGuia);
        return new JsonResponse($arNovedades);
    }

    /**
     * @param Request $request
     * @param int $codigoDespacho
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @Rest\Get("/transporte/api/app/guia/despacho/{codigoDespacho}")
     */
    public function listaDespacho(Request $request, $codigoDespacho = 0)
    {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $arGuia = $this->getDoctrine()->getManager()->getRepository(TteGuia::class)->apiDespacho($codigoDespacho);
        if ($arGuia) {
            return [
                'error' => false,
                'mensaje' => "Guia encontrada",
                'guias' => $arGuia,
            ];
        } else {
            return [
                'error' => true,
                'mensaje' => "No se encontraron guias",
                'guias' => null,
            ];
        }
        //return ;
    }

    /**
     * @param Request $request
     * @param int $codigoDespacho
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @Rest\Get("/transporte/api/app/guia/entrega/{codigoGuia}/{fecha}/{hora}/{usuario}")
     */
    public function entregaApp(Request $request, $codigoGuia = 0, $fecha, $hora, $usuario = "")
    {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $em = $this->getDoctrine()->getManager();
        $error = false;
        $mensaje = "";
        $arGuia = $em->getRepository(TteGuia::class)->find($codigoGuia);
        if ($arGuia) {
            if ($arGuia->getEstadoDespachado() == 1) {
                if ($arGuia->getEstadoEntregado() == 0) {
                    $fechaHora = date_create($fecha . " " . $hora);
                    $arGuia->setFechaEntrega($fechaHora);
                    $arGuia->setEstadoEntregado(1);
                    $em->persist($arGuia);
                    $em->flush();
                } else {
                    $error = true;
                    $mensaje = "La guia ya fue marcada como entregada";
                }
            } else {
                $error = true;
                $mensaje = "La guia no se ha despachado";
            }
        } else {
            $error = true;
            $mensaje = "No se encontro la guia";
        }
        return [
            'error' => $error,
            'mensaje' => $mensaje
        ];
    }

    /**
     * @param Request $request
     * @param int $codigoDespacho
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @Rest\Get("/transporte/api/app/guia/novedad/{codigoGuia}/{fecha}/{hora}/{usuario}/{codigoNovedad}")
     */
    public function novedadApp(Request $request, $codigoGuia = 0, $fecha, $hora, $usuario = "", $codigoNovedad = "")
    {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $em = $this->getDoctrine()->getManager();
        $error = false;
        $mensaje = "";
        $arGuia = $em->getRepository(TteGuia::class)->find($codigoGuia);
        if ($arGuia) {
            $arNovedadTipo = $em->getRepository(TteNovedadTipo::class)->find($codigoNovedad);
            if ($arNovedadTipo) {
                $arNovedad = new TteNovedad();
                $arNovedad->setGuiaRel($arGuia);
                $arNovedad->setNovedadTipoRel($arNovedadTipo);
                $arNovedad->setFechaRegistro(new \DateTime('now'));
                $arNovedad->setFechaAtencion(new \DateTime('now'));
                $arNovedad->setFechaSolucion(new \DateTime('now'));
                $arNovedad->setFechaReporte(new \DateTime('now'));
                $arNovedad->setFecha(new \DateTime('now'));
                //$arNovedad->setEstadoAtendido(true);
                $em->persist($arNovedad);
                $em->flush();
            } else {
                $error = true;
                $mensaje = "No se encontro este tipo novedad";
            }

        } else {
            $error = true;
            $mensaje = "No se encontro la guia";
        }
        return [
            'error' => $error,
            'mensaje' => $mensaje
        ];
    }

    /**
     * @param Request $request
     * @param int $codigoGuia
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @Rest\Post("/transporte/api/guia/entrega", name="transporte_api_guia_entrega")
     */
    public function entrega(Request $request)
    {
        $arrPost = json_decode($request->request->get("arrParametros"), true);
        return $this->getDoctrine()->getManager()->getRepository(TteGuia::class)->apiEntrega(
            $arrPost['codigoGuia'],
            $arrPost['fecha'],
            $arrPost['hora'],
            $arrPost['soporte']);
    }

    /**
     * @param Request $request
     * @param int $codigoGuia
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @Rest\Post("/transporte/api/guia/soporte", name="transporte_api_guia_soporte")
     */
    public function soporte(Request $request)
    {
        $arrPost = json_decode($request->request->get("arrParametros"), true);
        return $this->getDoctrine()->getManager()->getRepository(TteGuia::class)->apiSoporte($arrPost['codigoGuia']);
    }

    /**
     * @param Request $request
     * @param int $codigoGuia
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @Rest\Post("/transporte/api/guia/desembarco", name="transporte_api_guia_desembarco")
     */
    public function desembarco(Request $request)
    {
        $arOperacion = $this->getUser()->getOperacionRel();
        $arrPost = json_decode($request->request->get("arrParametros"), true);
        return $this->getDoctrine()->getManager()->getRepository(TteGuia::class)->apiDesembarco($arrPost['codigoGuia'], $arOperacion);
    }

    /**
     * @param Request $request
     * @param int $codigoGuia
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @Rest\Post("/transporte/api/guia/despacho/adicionar", name="transporte_api_guia_despacho_adicionar")
     */
    public function adicionarDespacho(Request $request)
    {
        $arrPost = json_decode($request->request->get("arrParametros"), true);
        return $this->getDoctrine()->getManager()->getRepository(TteGuia::class)->apiDespachoAdicionar($arrPost['codigoDespacho'], $arrPost['codigoGuia']);
    }

    /**
     * @param Request $request
     * @param int $codigoGuia
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @Rest\Post("/transporte/api/guia/factura/adicionar", name="transporte_api_guia_factura_adicionar")
     */
    public function adicionarFactura(Request $request)
    {
        $arrPost = json_decode($request->request->get("arrParametros"), true);
        return $this->getDoctrine()->getManager()->getRepository(TteGuia::class)->apiFacturaAdicionar(
            $arrPost['codigoFactura'],
            $arrPost['codigoGuia'],
            $arrPost['documento'],
            $arrPost['tipo']);
    }

    /**
     * @param Request $request
     * @param int $codigoGuia
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @Rest\Post("/transporte/api/guia/factura/planilla/adicionar", name="transporte_api_guia_factura_planilla_adicionar")
     */
    public function adicionarFacturaPlanilla(Request $request)
    {
        $arrPost = json_decode($request->request->get("arrParametros"), true);
        return $this->getDoctrine()->getManager()->getRepository(TteGuia::class)->apiFacturaPlanillaAdicionar(
            $arrPost['codigoFacturaPlanilla'],
            $arrPost['codigoGuia'],
            $arrPost['documento'],
            $arrPost['tipo']);
    }

    /**
     * @param Request $request
     * @param int $codigoGuia
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @Rest\Post("/transporte/api/guia/cumplido/adicionar", name="transporte_api_guia_cumplido_adicionar")
     */
    public function adicionarCumplido(Request $request)
    {
        $arrPost = json_decode($request->request->get("arrParametros"), true);
        return $this->getDoctrine()->getManager()->getRepository(TteGuia::class)->apiCumplidoAdicionar(
            $arrPost['codigoCumplido'],
            $arrPost['codigoGuia'],
            $arrPost['documento'],
            $arrPost['tipo']);
    }

    /**
     * @param Request $request
     * @return array
     * @Rest\Post("/transporte/api/guia/documental/adicionar", name="transporte_api_guia_documental_adicionar")
     */
    public function adicionarDocumental(Request $request)
    {
        $arrPost = json_decode($request->request->get("arrParametros"), true);
        return $this->getDoctrine()->getManager()->getRepository(TteGuia::class)->apiDocumentalAdicionar(
            $arrPost['codigoDocumental'],
            $arrPost['codigoGuia']);
    }


}
