<?php

namespace App\Controller\Turno\Administracion\Comercial\Cliente;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Turno\TurCliente;
use App\Entity\Turno\TurPuesto;
use App\Form\Type\Turno\ClienteType;
use App\Form\Type\Turno\PuestoType;
use App\General\General;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ClienteController extends ControllerListenerGeneral
{
    protected $clase= TurCliente::class;
    protected $claseNombre = "TurCliente";
    protected $modulo = "Turno";
    protected $funcion = "Administracion";
    protected $grupo = "Comercial";
    protected $nombre = "Cliente";

    /**
     * @param Request $request
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/turno/administracion/comercial/cliente/lista", name="turno_administracion_comercial_cliente_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
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
                General::get()->setExportar($em->getRepository($this->clase)->parametrosExcel(), "Excel");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arData = $request->request->get('ChkSeleccionar');
                $this->get("UtilidadesModelo")->eliminar(TurCliente::class, $arData);
            }
            if ($formBotonera->get('btnFiltrar')){
                $session->set('filtroTurCodigoCliente', $formBotonera->get('txtCodigoCliente')->getData());
                $session->set('filtroTurNombreCliente', $formBotonera->get('txtNombreCorto')->getData());
            }
        }

        return $this->render('turno/administracion/comercial/cliente/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),

        ]);
    }

    /**
     * @Route("/turno/administracion/comercial/cliente/nuevo/{id}", name="turno_administracion_comercial_cliente_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCliente = new TurCliente();
        if ($id != '0') {
            $arCliente = $em->getRepository(TurCliente::class)->find($id);
            if (!$arCliente) {
                return $this->redirect($this->generateUrl('turno_administracion_comercial_cliente_lista'));
            }
        }
        $form = $this->createForm(ClienteType::class, $arCliente);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arCliente);
                $em->flush();
                return $this->redirect($this->generateUrl('turno_administracion_comercial_cliente_detalle', ['id' => $arCliente->getCodigoClientePk()]));
            }
        }
        return $this->render('turno/administracion/comercial/cliente/nuevo.html.twig', [
            'arCliente' => $arCliente,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/turno/administracion/comercial/cliente/detalle/{id}", name="turno_administracion_comercial_cliente_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCliente = $em->getRepository(TurCliente::class)->find($id);
        $form = $this->createFormBuilder()
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn btn-sm btn-danger']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('btnEliminar')->isClicked()){
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $this->get('UtilidadesModelo')->eliminar(TurPuesto::class, $arrSeleccionados);
                return $this->redirect($this->generateUrl('turno_administracion_comercial_cliente_detalle', ['id' => $id]));
            }
        }
        $arPuestos = $em->getRepository(TurPuesto::class)->cliente($id);
        return $this->render('turno/administracion/comercial/cliente/detalle.html.twig', array(
            'arCliente' => $arCliente,
            'arPuestos'=>$arPuestos,
            'form' => $form->createView()

        ));
    }

    /**
     * @Route("/turno/administracion/comercial/puesto/nuevo/{codigoCliente}/{id}", name="turno_administracion_comercial_puesto_nuevo")
     */
    public function puestoNuevo(Request $request, $codigoCliente, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arPuesto = new TurPuesto();
        if ($id != '0') {
            $arPuesto = $em->getRepository(TurPuesto::class)->find($id);
            if (!$arPuesto) {
                return $this->redirect($this->generateUrl('turno_administracion_comercial_puesto_nuevo'));
            }
        }
        $form = $this->createForm(PuestoType::class, $arPuesto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arCliente = $em->getRepository(TurCliente::class)->find($codigoCliente);
                $arPuesto->setClienteRel($arCliente);
                $em->persist($arPuesto);
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('turno/administracion/comercial/cliente/nuevoPuesto.html.twig', [
            'arTurno' => $arPuesto,
            'form' => $form->createView()
        ]);
    }

}

