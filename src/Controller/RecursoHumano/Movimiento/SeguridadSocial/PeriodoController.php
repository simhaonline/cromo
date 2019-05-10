<?php

namespace App\Controller\RecursoHumano\Movimiento\SeguridadSocial;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Crm\CrmVisitaTipo;
use App\Entity\RecursoHumano\RhuSsPeriodo;
use App\Form\Type\RecursoHumano\SsPeriodoType;
use App\General\General;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Utilidades\Mensajes;

class PeriodoController extends ControllerListenerGeneral
{
    protected $clase = RhuSsPeriodo::class;
    protected $claseNombre = "RhuSsPeriodo";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Movimiento";
    protected $grupo = "SeguridadSocial";
    protected $nombre = "SsPeriodo";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/movimiento/seguridadsocial/periodo/lista", name="recursohumano_movimiento_seguridadsocial_ssperiodo_lista")
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
                General::get()->setExportar($em->createQuery($datos['queryBuilder'])->execute(), "Facturas");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(RhuSsPeriodo::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('transporte_movimiento_comercial_factura_lista'));
            }
        }

        return $this->render('recursohumano/movimiento/seguridadsocial/ssperiodo/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
        ]);

    }

    /**
     * @param Request $request
     * @param $id
     * @Route("recursohumano/movimiento/seguridadsocial/periodo/detalle/{id}", name="recursohumano_movimiento_seguridadsocial_ssperiodo_detalle")
     */
    public function detalle(Request $request, $id)
    {

    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("recursohumano/movimiento/seguridadsocial/periodo/nuevo/{id}", name="recursohumano_movimiento_seguridadsocial_ssperiodo_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        /**
         * @var $arSsPeriodo RhuSsPeriodo
         */
        $em = $this->getDoctrine()->getManager();
        if ($id == 0 ) {
            $arSsPeriodo = new RhuSsPeriodo();
        } else {
            $arSsPeriodo = $em->getRepository(RhuSsPeriodo::class)->find($id);
        }
        $form = $this->createForm(SsPeriodoType::class, $arSsPeriodo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arSsPeriodo);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_movimiento_seguridadsocial_ssperiodo_detalle', ['id' => $arSsPeriodo->getCodigoSsPeriodoPk()]));
            }
        }
        return $this->render('recursohumano/movimiento/seguridadsocial/ssperiodo/nuevo.html.twig', [
            'arSsPeriodo' => $arSsPeriodo,
            'form' => $form->createView()
        ]);
    }
}


