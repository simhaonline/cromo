<?php

namespace App\Controller\Financiero\Administracion\Contabilidad;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Financiero\FinAsiento;
use App\Entity\Financiero\FinCuenta;
use App\Form\Type\Financiero\CuentaType;
use App\General\General;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CuentaController extends ControllerListenerGeneral
{
    protected $class= FinCuenta::class;
    protected $claseNombre = "FinCuenta";
    protected $modulo = "Financiero";
    protected $funcion = "Administracion";
    protected $grupo = "Contabilidad";
    protected $nombre = "Cuenta";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/financiero/administracion/contabilidad/cuenta/lista", name="financiero_administracion_contabilidad_cuenta_lista")
     */
    public function lista(Request $request)
    {
        $this->request = $request;
        $em = $this->getDoctrine()->getManager();
        $formBotonera = BaseController::botoneraLista();
        $formBotonera->add('btnGenerarEstructura', SubmitType::class, array('label' => 'Generar estructura'));
        $formBotonera->handleRequest($request);
        $formFiltro = $this->getFiltroLista();
        $formFiltro->handleRequest($request);
        if ($formFiltro->isSubmitted() && $formFiltro->isValid()) {
            if ($formFiltro->get('btnFiltro')->isClicked()) {
                FuncionesController::generarSession($this->modulo,$this->nombre,$this->claseNombre,$formFiltro);
            }
        }
        $datos = $this->getDatosLista(true);
        if ($formBotonera->isSubmitted() && $formBotonera->isValid()) {
            if ($formBotonera->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->createQuery($datos['queryBuilder'])->execute(), "Cuentaes");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
//                $em->getRepository(TteFactura::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('financiero_administracion_contabilidad_cuenta_lista'));
            }
            if ($formBotonera->get('btnGenerarEstructura')->isClicked()) {
                $em->getRepository(FinCuenta::class)->generarEstructura();
                return $this->redirect($this->generateUrl('financiero_administracion_contabilidad_cuenta_lista'));
            }

        }
        return $this->render('financiero/administracion/contabilidad/cuenta/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
        ]);
    }

    /**
     * @Route("/financiero/administracion/contabilidad/cuenta/nuevo/{id}", name="financiero_administracion_contabilidad_cuenta_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCuenta = new FinCuenta();
        if ($id != '0') {
            $arCuenta = $em->getRepository(FinCuenta::class)->find($id);
            if (!$arCuenta) {
                return $this->redirect($this->generateUrl('financiero_administracion_contabilidad_cuenta_lista'));
            }
        }
        $form = $this->createForm(CuentaType::class, $arCuenta);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arCuenta->setNombreCorto($arCuenta->getNombre1() . " " . $arCuenta->getNombre2() . " " . $arCuenta->getApellido1() . " " . $arCuenta->getApellido2());
                $em->persist($arCuenta);
                $em->flush();
                return $this->redirect($this->generateUrl('financiero_administracion_financiero_cuenta_lista'));
            }
            if($form->get('guardarnuevo')->isClicked()){
                $arCuenta->setNombreCorto($arCuenta->getNombre1() . " " . $arCuenta->getNombre2() . " " . $arCuenta->getApellido1() . " " . $arCuenta->getApellido2());
                $em->persist($arCuenta);
                $em->flush();
                return $this->redirect($this->generateUrl('financiero_administracion_financiero_cuenta_nuevo',['id'=>0]));
            }

        }
        return $this->render('financiero/administracion/contabilidad/cuenta/nuevo.html.twig', [
            'arCuenta' => $arCuenta,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/financiero/administracion/contabilidad/cuenta/detalle/{id}", name="financiero_administracion_contabilidad_cuenta_detalle")
     */
    public function detalle(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $arCuenta = $em->getRepository(FinCuenta::class)->find($id);
        $form = $this->createFormBuilder()
            ->getForm();
        $form->handleRequest($request);

        return $this->render('financiero/administracion/contabilidad/cuenta/detalle.html.twig', array(
            'arCuenta' => $arCuenta,
            'form' => $form->createView()
        ));

    }

}

