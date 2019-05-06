<?php

namespace App\Controller\Turno\Administracion\Cliente;

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
    protected $grupo = "Cliente";
    protected $nombre = "Cliente";

    /**
     * @param Request $request
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/turno/administracion/cliente/cliente/lista", name="turno_administracion_cliente_cliente_lista")
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

        return $this->render('turno/administracion/cliente/lista.html.twig', [
            'arrDatosLista' => $this->getDatosLista(),
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),

        ]);
    }

    /**
     * @Route("/turno/administracion/cliente/nuevo/{id}", name="turno_administracion_cliente_cliente_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCliente = new TurCliente();
        if ($id != '0') {
            $arCliente = $em->getRepository(TurCliente::class)->find($id);
            if (!$arCliente) {
                return $this->redirect($this->generateUrl('turno_administracion_cliente_cliente_lista'));
            }
        }
        $form = $this->createForm(ClienteType::class, $arCliente);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arCliente);
                $em->flush();
                return $this->redirect($this->generateUrl('turno_administracion_cliente__detalle', ['id' => $arCliente->getCodigoClientePk()]));
            }
        }
        return $this->render('turno/administracion/cliente/nuevo.html.twig', [
            'arCliente' => $arCliente,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/turno/administracion/cliente/detalle/{id}", name="turno_administracion_cliente__detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCliente = $em->getRepository(TurCliente::class)->find($id);
        $arPuestos = $em->getRepository(TurPuesto::class)->findBy( ['codigoClienteFk' => $id],['codigoPuestoPk' => 'ASC']);
        //dd($arPuestos);
        $form = $this->createFormBuilder()
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('btnEliminar')->isClicked()){
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $this->get('UtilidadesModelo')->eliminar(TurPuesto::class, $arrSeleccionados);
                return $this->redirect($this->generateUrl('turno_administracion_cliente__detalle', ['id' => $id]));
            }
        }

        return $this->render('turno/administracion/cliente/detalle.html.twig', array(
            'arCliente' => $arCliente,
            'arPuestos'=>$arPuestos,
            'form' => $form->createView()

        ));
    }

    /**
     * @Route("/turno/administracion/puesto/nuevo/{id}/{codigoCliente}", name="turno_administracion_cliente_clientepuesto_nuevo")
     */
    public function puestoNuevo(Request $request, $id, $codigoCliente)
    {
        $em = $this->getDoctrine()->getManager();
        $arTurno = new TurPuesto();
        if ($id != '0') {
            $arTurno = $em->getRepository(TurPuesto::class)->find($id);
            if (!$arTurno) {
                return $this->redirect($this->generateUrl('turno_administracion_cliente_puesto_lista'));
            }
        }
        $form = $this->createForm(PuestoType::class, $arTurno);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arCliente = $em->getRepository(TurCliente::class)->find($codigoCliente);
                $arTurno->setClienteRel($arCliente);
                $em->persist($arTurno);
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('turno/administracion/cliente/nuevoPuesto.html.twig', [
            'arTurno' => $arTurno,
            'form' => $form->createView()
        ]);
    }

}

