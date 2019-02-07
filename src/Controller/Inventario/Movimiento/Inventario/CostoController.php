<?php

namespace App\Controller\Inventario\Movimiento\Inventario;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Inventario\InvCostoCosto;
use App\Entity\Inventario\InvItem;
use App\Entity\Inventario\InvCosto;
use App\Entity\Inventario\InvCostoDetalle;
use App\Entity\Inventario\InvCostoTipo;
use App\Entity\Inventario\InvTercero;
use App\Form\Type\Inventario\CostoCostoType;
use App\Formato\Inventario\Costo;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Type\Inventario\CostoType;

class CostoController extends ControllerListenerGeneral
{
    protected $class = InvCosto::class;
    protected $claseNombre = "InvCosto";
    protected $modulo = "Inventario";
    protected $funcion = "Movimiento";
    protected $grupo = "Inventario";
    protected $nombre = "Costo";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/inventario/movimiento/inventario/costo/lista", name="inventario_movimiento_inventario_costo_lista")
     */
    public function lista(Request $request)
    {
        $this->request = $request;
        $em = $this->getDoctrine()->getManager();
        $formBotonera = BaseController::botoneraLista();
        $formBotonera->handleRequest($request);
        $formFiltro = $this->getFiltroLista();
        $formFiltro->handleRequest($request);

        if ($formFiltro->isSubmitted() && $formFiltro->isValid()) {
            if ($formFiltro->get('btnFiltro')->isClicked()) {
                FuncionesController::generarSession($this->modulo, $this->nombre, $this->claseNombre, $formFiltro);
            }
        }
        $datos = $this->getDatosLista(true);
        if ($formBotonera->isSubmitted() && $formBotonera->isValid()) {
            if ($formBotonera->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->createQuery($datos['queryBuilder'])->execute(), "Costo");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(InvCosto::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('cartera_movimiento_recibo_recibo_lista'));
            }
        }
        return $this->render('inventario/movimiento/inventario/costo/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/inventario/movimiento/inventario/costo/nuevo/{id}", name="inventario_movimiento_inventario_costo_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCosto = new InvCosto();
        if ($id != 0) {
            $arCosto = $em->getRepository(InvCosto::class)->find($id);
        }
        $form = $this->createForm(CostoType::class, $arCosto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                if ($id == 0) {
                    $arCosto->setUsuario($this->getUser()->getUserName());
                }
                $em->persist($arCosto);
                $em->flush();
                return $this->redirect($this->generateUrl('inventario_movimiento_inventario_costo_detalle', ['id' => $arCosto->getCodigoCostoPk()]));
            }
        }
        return $this->render('inventario/movimiento/inventario/costo/nuevo.html.twig', [
            'form' => $form->createView(),
            'arCosto' => $arCosto
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route("/inventario/movimiento/inventario/costo/detalle/{id}", name="inventario_movimiento_inventario_costo_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $paginator = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $arCosto = $em->getRepository(InvCosto::class)->find($id);
        $form = Estandares::botonera($arCosto->getEstadoAutorizado(), $arCosto->getEstadoAprobado(), $arCosto->getEstadoAnulado());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrControles = $request->request->all();
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(InvCosto::class)->autorizar($arCosto);
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(InvCosto::class)->desautorizar($arCosto);
            }
            if ($form->get('btnImprimir')->isClicked()) {
                $objFormatocosto = new Costo();
                $objFormatocosto->Generar($em, $id);
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(InvCosto::class)->aprobar($arCosto);
            }
            if ($form->get('btnAnular')->isClicked()) {
                $em->getRepository(InvCosto::class)->anular($arCosto);
            }
            return $this->redirect($this->generateUrl('inventario_movimiento_inventario_costo_detalle', ['id' => $id]));
        }
        $arCostoDetalles = $paginator->paginate($em->getRepository(InvCostoDetalle::class)->costo($id), $request->query->getInt('page', 1), 10);
        return $this->render('inventario/movimiento/inventario/costo/detalle.html.twig', [
            'form' => $form->createView(),
            'arCostoDetalles' => $arCostoDetalles,
            'arCosto' => $arCosto,
            'clase' => array('clase'=>'InvCosto', 'codigo' => $id),
        ]);
    }
    
}
