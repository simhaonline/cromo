<?php

namespace App\Controller\Transporte\Administracion\Vehiculo;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Controller\MaestroController;
use App\Formato\Transporte\Vehiculo;
use App\General\General;
use App\Utilidades\Mensajes;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Entity\Transporte\TteVehiculo;
use App\Form\Type\Transporte\VehiculoType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class VehiculoController extends MaestroController
{
    public $tipo = "administracion";
    public $modelo = "TteVehiculo";

    protected $class = TteVehiculo::class;
    protected $claseNombre = "TteVehiculo";
    protected $modulo = "Transporte";
    protected $funcion = "Administracion";
    protected $grupo = "Transporte";
    protected $nombre = "Vehiculo";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/transporte/administracion/vehiculo/lista", name="transporte_administracion_transporte_vehiculo_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $this->request = $request;
        $em = $this->getDoctrine()->getManager();
        $formBotonera = MaestroController::botoneraLista();
        $formBotonera->handleRequest($request);
        $formFiltro = $this->getFiltroLista();
        $formFiltro->handleRequest($request);
        if ($formFiltro->isSubmitted() && $formFiltro->isValid()) {
            if ($formFiltro->get('btnFiltro')->isClicked()) {
                FuncionesController::generarSession($this->modulo, $this->nombre, $this->claseNombre, $formFiltro);
            }
        }
        $datos = $this->getDatosLista(true, true, $paginator);
        if ($formBotonera->isSubmitted() && $formBotonera->isValid()) {
            if ($formBotonera->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(TteVehiculo::class)->lista()->getQuery()->getResult(), "Vehiculos");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
//                $em->getRepository(TteDespachoRecogida::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('transporte_administracion_transporte_vehiculo_lista'));
            }
        }

        return $this->render('transporte/administracion/vehiculo/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
        ]);

    }

    /**
     * @Route("/transporte/administracion/vehiculo/nuevo/{id}", name="transporte_administracion_transporte_vehiculo_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $em = $this->getDoctrine()->getManager();
        $arVehiculo = new TteVehiculo();
        if ($id != '0') {
            $arVehiculo = $em->getRepository(TteVehiculo::class)->find($id);
            if (!$arVehiculo) {
                return $this->redirect($this->generateUrl('transporte_administracion_transporte_vehiculo_lista'));
            }
        }
        $form = $this->createForm(VehiculoType::class, $arVehiculo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                if ($id == '0') {
                    $arVehiculoValidar = $em->getRepository(TteVehiculo::class)->find($form->getData('codigoVehiculoPk'));
                    if (!$arVehiculoValidar) {
                        $em->persist($arVehiculo);
                        $em->flush();
                        return $this->redirect($this->generateUrl('transporte_administracion_transporte_vehiculo_lista'));
                    } else {
                        Mensajes::error("El vehiculo ya existe");
                    }
                } else {
                    $em->persist($arVehiculo);
                    $em->flush();
                    return $this->redirect($this->generateUrl('transporte_administracion_transporte_vehiculo_lista'));
                }
            }
        }
        return $this->render('transporte/administracion/vehiculo/nuevo.html.twig', [
            'arVehiculo' => $arVehiculo,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return Response
     * @Route("/transporte/administracion/vehiculo/detalle/{id}", name="transporte_administracion_transporte_vehiculo_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arVehiculo = $em->getRepository(TteVehiculo::class)->find($id);
        $form = $this->createFormBuilder()
            ->add('btnImprimir', SubmitType::class, array('label' => 'Imprimir'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImprimir')->isClicked()) {
                $formato = new Vehiculo();
                $formato->Generar($em, $id);
            }
        }
        return $this->render('transporte/administracion/vehiculo/detalle.html.twig', [
            'clase' => array('clase' => 'TteVehiculo', 'codigo' => $id),
            'arVehiculo' => $arVehiculo,
            'form' => $form->createView()]);
    }

}

