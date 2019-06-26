<?php

namespace App\Controller\Transporte\Api;

use App\Entity\General\GenConfiguracion;
use App\Entity\Seguridad\Usuario;
use App\Entity\Transporte\TteCiudad;
use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteCondicion;
use App\Entity\Transporte\TteCondicionFlete;
use App\Entity\Transporte\TteCondicionManejo;
use App\Entity\Transporte\TteDescuentoZona;
use App\Entity\Transporte\TteDestinatario;
use App\Entity\Transporte\TteEmpaque;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteGuiaCarga;
use App\Entity\Transporte\TteGuiaTipo;
use App\Entity\Transporte\TteNovedad;
use App\Entity\Transporte\TteNovedadTipo;
use App\Entity\Transporte\TtePrecioDetalle;
use App\Entity\Transporte\TteProducto;
use App\Entity\Transporte\TteRecibo;
use App\Entity\Transporte\TteServicio;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ApiWindowsController extends FOSRestController
{

    /**
     * @return array
     * @Rest\Post("/transporte/api/windows/guia/nuevo")
     */
    public function guiaNuevo(Request $request) {
        try {
            $em = $this->getDoctrine()->getManager();
            $raw = json_decode($request->getContent(), true);
            return $em->getRepository(TteGuia::class)->apiWindowsNuevo($raw);
        } catch (\Exception $e) {
            return [
                'error' => "Ocurrio un error en la api " . $e->getMessage(),
            ];
        }
    }

    /**
     * @return array
     * @Rest\Post("/transporte/api/windows/guia/detalle")
     */
    public function guiaDetalle(Request $request) {
        try {
            $em = $this->getDoctrine()->getManager();
            $raw = json_decode($request->getContent(), true);
            return $em->getRepository(TteGuia::class)->apiWindowsDetalle($raw);
        } catch (\Exception $e) {
            return [
                'error' => "Ocurrio un error en la api " . $e->getMessage(),
            ];
        }
    }

    /**
     * @return array
     * @Rest\Post("/transporte/api/windows/guia/imprimir")
     */
    public function guiaImprimir(Request $request) {
        try {
            $em = $this->getDoctrine()->getManager();
            $raw = json_decode($request->getContent(), true);
            return $em->getRepository(TteGuia::class)->apiWindowsImprimir($raw);
        } catch (\Exception $e) {
            return [
                'error' => "Ocurrio un error en la api " . $e->getMessage(),
            ];
        }
    }

    /**
     * @return array
     * @Rest\Get("/transporte/api/windows/guiatipo/lista")
     */
    public function guiaTipoLista(Request $request) {
        try {
            $em = $this->getDoctrine()->getManager();
            $raw = json_decode($request->getContent(), true);
            return $em->getRepository(TteGuiaTipo::class)->apiWindowsLista($raw);
        } catch (\Exception $e) {
            return [
                'error' => "Ocurrio un error en la api " . $e->getMessage(),
            ];
        }
    }

    /**
     * @return array
     * @Rest\Post("/transporte/api/windows/guiatipo/detalle")
     */
    public function guiaTipoDetalle(Request $request) {
        try {
            $em = $this->getDoctrine()->getManager();
            $raw = json_decode($request->getContent(), true);
            return $em->getRepository(TteGuiaTipo::class)->apiWindowsDetalle($raw);
        } catch (\Exception $e) {
            return [
                'error' => "Ocurrio un error en la api " . $e->getMessage(),
            ];
        }
    }

    /**
     * @return array
     * @Rest\Get("/transporte/api/windows/servicio/lista")
     */
    public function servicioLista(Request $request) {
        try {
            $em = $this->getDoctrine()->getManager();
            $raw = json_decode($request->getContent(), true);
            return $em->getRepository(TteServicio::class)->apiWindowsLista($raw);
        } catch (\Exception $e) {
            return [
                'error' => "Ocurrio un error en la api " . $e->getMessage(),
            ];
        }
    }

    /**
     * @return array
     * @Rest\Get("/transporte/api/windows/producto/lista")
     */
    public function productoLista(Request $request) {
        try {
            $em = $this->getDoctrine()->getManager();
            $raw = json_decode($request->getContent(), true);
            return $em->getRepository(TteProducto::class)->apiWindowsLista($raw);
        } catch (\Exception $e) {
            return [
                'error' => "Ocurrio un error en la api " . $e->getMessage(),
            ];
        }
    }

    /**
     * @return array
     * @Rest\Get("/transporte/api/windows/empaque/lista")
     */
    public function empaqueLista(Request $request) {
        try {
            $em = $this->getDoctrine()->getManager();
            $raw = json_decode($request->getContent(), true);
            return $em->getRepository(TteEmpaque::class)->apiWindowsLista($raw);
        } catch (\Exception $e) {
            return [
                'error' => "Ocurrio un error en la api " . $e->getMessage(),
            ];
        }
    }

    /**
     * @return array
     * @Rest\Post("/transporte/api/windows/cliente/buscar")
     */
    public function clienteBuscar(Request $request) {
        try {
            $em = $this->getDoctrine()->getManager();
            $raw = json_decode($request->getContent(), true);
            return $em->getRepository(TteCliente::class)->apiWindowsBuscar($raw);
        } catch (\Exception $e) {
            return [
                'error' => "Ocurrio un error en la api " . $e->getMessage(),
            ];
        }
    }

    /**
     * @return array
     * @Rest\Post("/transporte/api/windows/cliente/detalle")
     */
    public function clienteDetalle(Request $request) {
        try {
            $em = $this->getDoctrine()->getManager();
            $raw = json_decode($request->getContent(), true);
            return $em->getRepository(TteCliente::class)->apiWindowsDetalle($raw);
        } catch (\Exception $e) {
            return [
                'error' => "Ocurrio un error en la api " . $e->getMessage(),
            ];
        }
    }

    /**
     * @return array
     * @Rest\Post("/transporte/api/windows/ciudad/buscar")
     */
    public function ciudadBuscar(Request $request) {
        try {
            $em = $this->getDoctrine()->getManager();
            $raw = json_decode($request->getContent(), true);
            return $em->getRepository(TteCiudad::class)->apiWindowsBuscar($raw);
        } catch (\Exception $e) {
            return [
                'error' => "Ocurrio un error en la api " . $e->getMessage(),
            ];
        }
    }

    /**
     * @return array
     * @Rest\Post("/transporte/api/windows/ciudad/detalle")
     */
    public function ciudadDetalle(Request $request) {
        try {
            $em = $this->getDoctrine()->getManager();
            $raw = json_decode($request->getContent(), true);
            return $em->getRepository(TteCiudad::class)->apiWindowsDetalle($raw);
        } catch (\Exception $e) {
            return [
                'error' => "Ocurrio un error en la api " . $e->getMessage(),
            ];
        }
    }

    /**
     * @return array
     * @Rest\Post("/transporte/api/windows/destinatario/buscar")
     */
    public function destinatarioBuscar(Request $request) {
        try {
            $em = $this->getDoctrine()->getManager();
            $raw = json_decode($request->getContent(), true);
            return $em->getRepository(TteDestinatario::class)->apiWindowsBuscar($raw);
        } catch (\Exception $e) {
            return [
                'error' => "Ocurrio un error en la api " . $e->getMessage(),
            ];
        }
    }

    /**
     * @return array
     * @Rest\Post("/transporte/api/windows/destinatario/detalle")
     */
    public function destinatarioDetalle(Request $request) {
        try {
            $em = $this->getDoctrine()->getManager();
            $raw = json_decode($request->getContent(), true);
            return $em->getRepository(TteDestinatario::class)->apiWindowsDetalle($raw);
        } catch (\Exception $e) {
            return [
                'error' => "Ocurrio un error en la api " . $e->getMessage(),
            ];
        }
    }

    /**
     * @return array
     * @Rest\Post("/transporte/api/windows/condicion/buscar")
     */
    public function condicionBuscar(Request $request) {
        try {
            $em = $this->getDoctrine()->getManager();
            $raw = json_decode($request->getContent(), true);
            return $em->getRepository(TteCondicion::class)->apiWindowsBuscar($raw);
        } catch (\Exception $e) {
            return [
                'error' => "Ocurrio un error en la api " . $e->getMessage(),
            ];
        }
    }

    /**
     * @return array
     * @Rest\Post("/transporte/api/windows/condicion/detalle")
     */
    public function condicionDetalle(Request $request) {
        try {
            $em = $this->getDoctrine()->getManager();
            $raw = json_decode($request->getContent(), true);
            return $em->getRepository(TteCondicion::class)->apiWindowsDetalle($raw);
        } catch (\Exception $e) {
            return [
                'error' => "Ocurrio un error en la api " . $e->getMessage(),
            ];
        }
    }

    /**
     * @return array
     * @Rest\Post("/transporte/api/windows/preciodetalle/detalle")
     */
    public function precioDetalleDetalle(Request $request) {
        try {
            $em = $this->getDoctrine()->getManager();
            $raw = json_decode($request->getContent(), true);
            return $em->getRepository(TtePrecioDetalle::class)->apiWindowsDetalle($raw);
        } catch (\Exception $e) {
            return [
                'error' => "Ocurrio un error en la api " . $e->getMessage(),
            ];
        }
    }

    /**
     * @return array
     * @Rest\Post("/transporte/api/windows/descuentozona/detalle")
     */
    public function descuentoZonaDetalle(Request $request) {
        try {
            $em = $this->getDoctrine()->getManager();
            $raw = json_decode($request->getContent(), true);
            return $em->getRepository(TteDescuentoZona::class)->apiWindowsDetalle($raw);
        } catch (\Exception $e) {
            return [
                'error' => "Ocurrio un error en la api " . $e->getMessage(),
            ];
        }
    }

    /**
     * @return array
     * @Rest\Post("/transporte/api/windows/preciodetalle/detalleproducto")
     */
    public function precioDetalleDetalleProducto(Request $request) {
        try {
            $em = $this->getDoctrine()->getManager();
            $raw = json_decode($request->getContent(), true);
            return $em->getRepository(TtePrecioDetalle::class)->apiWindowsDetalleProducto($raw);
        } catch (\Exception $e) {
            return [
                'error' => "Ocurrio un error en la api " . $e->getMessage(),
            ];
        }
    }

    /**
     * @return array
     * @Rest\Post("/transporte/api/windows/usuario/validar")
     */
    public function usuarioValidar(Request $request) {
        try {
            $em = $this->getDoctrine()->getManager();
            $raw = json_decode($request->getContent(), true);
            return $em->getRepository(Usuario::class)->apiWindowsValidar($raw);
        } catch (\Exception $e) {
            return [
                'error' => "Ocurrio un error en la api " . $e->getMessage(),
            ];
        }
    }

    /**
     * @return array
     * @Rest\Post("/transporte/api/windows/general/configuracion")
     */
    public function configuracion(Request $request) {
        try {
            $em = $this->getDoctrine()->getManager();
            $raw = json_decode($request->getContent(), true);
            return $em->getRepository(GenConfiguracion::class)->apiWindowsConfiguracion($raw);
        } catch (\Exception $e) {
            return [
                'error' => "Ocurrio un error en la api " . $e->getMessage(),
            ];
        }
    }

    /**
     * @return array
     * @Rest\Post("/transporte/api/windows/recibo/nuevo")
     */
    public function reciboNuevo(Request $request) {
        try {
            $em = $this->getDoctrine()->getManager();
            $raw = json_decode($request->getContent(), true);
            return $em->getRepository(TteRecibo::class)->apiWindowsNuevo($raw);
        } catch (\Exception $e) {
            return [
                'error' => "Ocurrio un error en la api " . $e->getMessage(),
            ];
        }
    }

    /**
     * @return array
     * @Rest\Post("/transporte/api/windows/recibo/detalle")
     */
    public function reciboDetalle(Request $request) {
        try {
            $em = $this->getDoctrine()->getManager();
            $raw = json_decode($request->getContent(), true);
            return $em->getRepository(TteRecibo::class)->apiWindowsDetalle($raw);
        } catch (\Exception $e) {
            return [
                'error' => "Ocurrio un error en la api " . $e->getMessage(),
            ];
        }
    }

    /**
     * @return array
     * @Rest\Post("/transporte/api/windows/recibo/imprimir")
     */
    public function reciboImprimir(Request $request) {
        try {
            $em = $this->getDoctrine()->getManager();
            $raw = json_decode($request->getContent(), true);
            return $em->getRepository(TteRecibo::class)->apiWindowsImprimir($raw);
        } catch (\Exception $e) {
            return [
                'error' => "Ocurrio un error en la api " . $e->getMessage(),
            ];
        }
    }
    /**
     * @return array
     * @Rest\Post("/transporte/api/windows/guiacarga/detalle")
     */
    public function guiaCargaDetalle(Request $request) {
        try {
            $em = $this->getDoctrine()->getManager();
            $raw = json_decode($request->getContent(), true);
            return $em->getRepository(TteGuiaCarga::class)->apiWindowsDetalle($raw);
        } catch (\Exception $e) {
            return [
                'error' => "Ocurrio un error en la api " . $e->getMessage(),
            ];
        }
    }

    /**
     * @return array
     * @Rest\Post("/transporte/api/windows/condicionflete/cliente")
     */
    public function condicionFleteCliente(Request $request) {
        try {
            $em = $this->getDoctrine()->getManager();
            $raw = json_decode($request->getContent(), true);
            return $em->getRepository(TteCondicionFlete::class)->apiWindowsCliente($raw);
        } catch (\Exception $e) {
            return [
                'error' => "Ocurrio un error en la api " . $e->getMessage(),
            ];
        }
    }

    /**
     * @return array
     * @Rest\Post("/transporte/api/windows/condicionflete/liquidar")
     */
    public function condicionFleteLiquidar(Request $request) {
        try {
            $em = $this->getDoctrine()->getManager();
            $raw = json_decode($request->getContent(), true);
            return $em->getRepository(TteCondicionFlete::class)->apiWindowsLiquidar($raw);
        } catch (\Exception $e) {
            return [
                'error' => "Ocurrio un error en la api " . $e->getMessage(),
            ];
        }
    }

    /**
     * @return array
     * @Rest\Post("/transporte/api/windows/condicionmanejo/liquidar")
     */
    public function condicionManejoLiquidar(Request $request) {
        try {
            $em = $this->getDoctrine()->getManager();
            $raw = json_decode($request->getContent(), true);
            return $em->getRepository(TteCondicionManejo::class)->apiWindowsLiquidar($raw);
        } catch (\Exception $e) {
            return [
                'error' => "Ocurrio un error en la api " . $e->getMessage(),
            ];
        }
    }

}
