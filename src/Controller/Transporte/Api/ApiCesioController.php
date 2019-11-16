<?php

namespace App\Controller\Transporte\Api;

use App\Entity\Documental\DocConfiguracion;
use App\Entity\Documental\DocDirectorio;
use App\Entity\Documental\DocMasivo;
use App\Entity\Documental\DocMasivoTipo;
use App\Entity\Transporte\TteCiudad;
use App\Entity\Transporte\TteDespacho;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TtePrecioDetalle;
use App\Entity\Transporte\TteUbicacion;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;

class ApiCesioController extends FOSRestController
{
    /**
     * @param Request $request
     * @param string $ciudadOrigen
     * @param string $ciudadDestino
     * @param string $producto
     * @param int $peso
     * @param int $listaPrecio
     * @return JsonResponse
     * @Rest\Get("/transporte/api/cesio/precio/calcular/{ciudadOrigen}/{ciudadDestino}/{producto}/{peso}/{listaPrecio}")
     */
    public function guiaCrear(Request $request, $ciudadOrigen = '', $ciudadDestino = '', $producto = '', $peso = 0, $listaPrecio = 0)
    {
        $em = $this->getDoctrine()->getManager();
        $flete = 0;
        $arPrecioDetalle = $em->getRepository(TtePrecioDetalle::class)->findOneBy(['codigoProductoFk' => $producto, 'codigoCiudadOrigenFk' => $ciudadOrigen, 'codigoCiudadDestinoFk' => $ciudadDestino, 'codigoPrecioFk' => $listaPrecio]);
        if ($arPrecioDetalle) {
            $flete = $arPrecioDetalle->getVrPeso() * $peso;
        }
        return new JsonResponse($flete);
    }

    /**
     * @Rest\Post("/transporte/api/cesio/guia/lista/cliente")
     */
    public function guiaClienteLista(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $raw = json_decode($request->getContent(), true);
        $codigoCliente = $raw['codigoCliente']?? '';
        $fechaDesde = $raw['fechaDesde']?? '';
        $fechaHasta = $raw['fechaHasta']?? '';
        $numero = $raw['numero']?? '';
        $documento = $raw['documento']?? '';
        if($codigoCliente && $fechaDesde && $fechaHasta)  {
            return $arGuias = $em->getRepository(TteGuia::class)->apiCesioListaCliente($codigoCliente,$fechaDesde,$fechaHasta, $numero, $documento);
        } else {
            return [
                'error' => "Faltan datos en el post"
            ];
        }
    }

    /**
     * @return array
     * @Rest\Post("/transporte/api/cesio/guia/liquidar")
     */
    public function guiaLiquidar(Request $request) {
        try {
            $em = $this->getDoctrine()->getManager();
            $raw = json_decode($request->getContent(), true);
            $cliente = $raw['cliente']?? null;
            $condicion = $raw['condicion']?? null;
            $precio = $raw['precio']?? null;
            $origen = $raw['origen']?? null;
            $destino = $raw['destino']?? null;
            $producto = $raw['producto']?? null;
            $zona = $raw['zona']?? null;
            $tipoLiquidacion = $raw['tipoLiquidacion']?? null;
            $unidades = $raw['unidades']?? null;
            $peso = $raw['peso']?? null;
            $declarado = $raw['declarado']?? null;
            if($destino) {
                $arCiudad = $em->getRepository(TteCiudad::class)->find($destino);
                if($arCiudad) {
                    if($arCiudad->getCodigoZonaFk()) {
                        $zona = $arCiudad->getCodigoZonaFk();
                    }
                }
            }
            if($cliente && $condicion && $precio && $origen && $destino && $producto && $tipoLiquidacion && $unidades) {
                return $em->getRepository(TteGuia::class)->liquidar($cliente, $condicion, $precio, $origen, $destino, $producto, $zona, $tipoLiquidacion, $unidades, $peso, $declarado);
            } else {
                return [
                    'error' => "faltan datos para la api cromo ",
                ];
            }

        } catch (\Exception $e) {
            return [
                'error' => "Ocurrio un error en la api " . $e->getMessage(),
            ];
        }
    }

    /**
     * @Rest\Post("/transporte/api/cesio/guia/entrega", name="transporte_api_cesio_guia_entrega")
     */
    public function entrega(Request $request) {
        try {
            $em = $this->getDoctrine()->getManager();
            $raw = json_decode($request->getContent(), true);
            $guia = $raw['guia']??null;
            $imagen = $raw['imageString']??null;
            $usuario = $raw['usuario']??null;
            $arGuia = $em->getRepository(TteGuia::class)->find($guia);
            if ($arGuia) {
                if ($arGuia->getEstadoDespachado() == 1) {
                    if ($arGuia->getEstadoEntregado() == 0) {
                        $arGuia->setFechaEntrega(new \DateTime('now'));
                        $arGuia->setEstadoEntregado(1);
                        $arGuia->setUsuarioEntrega($usuario);
                        $em->persist($arGuia);
                        $em->flush();

                        if($imagen) {
                            $tipo = "TteGuia";
                            $arMasivoTipo = $em->getRepository(DocMasivoTipo::class)->find($tipo);
                            $arrConfiguracion = $em->getRepository(DocConfiguracion::class)->archivoMasivo();
                            $directorioDestino = $arrConfiguracion['rutaAlmacenamiento'] . "/masivo/";
                            if(file_exists($directorioDestino)) {
                                $arDirectorio = $em->getRepository(DocDirectorio::class)->findOneBy(array('tipo' => 'M', 'codigoMasivoTipoFk' => $tipo));
                                if(!$arDirectorio) {
                                    $arDirectorio = new DocDirectorio();
                                    $arDirectorio->setCodigoMasivoTipoFk($tipo);
                                    $arDirectorio->setDirectorio(1);
                                    $arDirectorio->setNumeroArchivos(0);
                                    $arDirectorio->setTipo('M');
                                    $em->persist($arDirectorio);
                                    $em->flush();
                                }
                                if($arDirectorio) {
                                    $arDirectorio = $em->getRepository(DocDirectorio::class)->find($arDirectorio->getCodigoDirectorioPk());
                                    if($arDirectorio->getNumeroArchivos() >= 50000) {
                                        $arDirectorio->setNumeroArchivos(0);
                                        $arDirectorio->setDirectorio($arDirectorio->getDirectorio()+1);
                                        $em->persist($arDirectorio);
                                        $em->flush();
                                    }
                                    $directorio = $directorioDestino . $tipo . "/" . $arDirectorio->getDirectorio() . "/";
                                    if(!file_exists($directorio)) {
                                        if(!mkdir($directorio, 0777, true)) {
                                            return [
                                                'error' => "Fallo al crear directorio... " . $directorio,
                                            ];
                                        }
                                    }

                                    $archivoDestino = rand(100000, 999999) . "_" . $guia . ".jpg";
                                    $destino = $directorio . $archivoDestino;
                                    $Base64Img = base64_decode($imagen);
                                    file_put_contents($destino, $Base64Img);
                                    $tamano = filesize ( $destino );
                                    $arMasivo = new DocMasivo();
                                    $arMasivo->setIdentificador($guia);
                                    $arMasivo->setMasivoTipoRel($arMasivoTipo);
                                    $arMasivo->setArchivo($guia . ".jpg");
                                    $arMasivo->setExtension('image/jpeg');
                                    $arMasivo->setDirectorio($arDirectorio->getDirectorio());
                                    $arMasivo->setTamano($tamano);
                                    $arMasivo->setArchivoDestino($archivoDestino);
                                    $em->persist($arMasivo);
                                    $arDirectorio->setNumeroArchivos($arDirectorio->getNumeroArchivos()+1);
                                    $em->persist($arDirectorio);
                                    $em->flush();
                                }
                            } else {
                                return [
                                    'error' => "No existe el directorio " . $directorioDestino,
                                ];

                            }
                        }
                    }
                }
            }
            return ['estado' => 'ok'];
        } catch (\Exception $e) {
            return [
                'error' => "Ocurrio un error en la api " . $e->getMessage(),
            ];
        }
    }

    /**
     * @Rest\Post("/transporte/api/cesio/despacho/ubicacion", name="transporte_api_cesio_despacho_ubicacion")
     */
    public function ubicacion(Request $request) {
        try {
            $em = $this->getDoctrine()->getManager();
            $raw = json_decode($request->getContent(), true);
            $despacho = $raw['guia']??null;
            $usuario = $raw['usuario']??null;
            $latitud = $raw['latitud']??null;
            $longitud = $raw['longitud']??null;
            if($despacho) {
                $arDespacho = $em->getRepository(TteDespacho::class)->find($despacho);
                if($arDespacho) {
                    $arUbicacion = new TteUbicacion();
                    $arUbicacion->setDespachoRel($arDespacho);
                    $arUbicacion->setLatitud($latitud);
                    $arUbicacion->setLongitud($longitud);
                    $arUbicacion->setUsuario($usuario);
                    $em->persist($arUbicacion);
                    $em->flush();
                }
            }
            return ['estado' => 'ok'];
        } catch (\Exception $e) {
            return [
                'error' => "Ocurrio un error en la api " . $e->getMessage(),
            ];
        }
    }
}