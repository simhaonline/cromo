<?php

namespace App\Controller\Turno\Movimiento\Contrato;

use App\Controller\Estructura\ControllerListenerGeneral;
use App\Entity\Turno\TurContrato;
use App\Form\Type\Turno\ContratoType;
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

class ContratoController extends ControllerListenerGeneral
{
    protected $clase= TurContrato::class;
    protected $claseFormulario = ContratoType::class;
    protected $claseNombre = "TurContrato";
    protected $modulo = "Turno";
    protected $funcion = "Movimiento";
    protected $grupo = "Contrato";
    protected $nombre = "Contrato";

    /**
     * @param Request $request
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/turno/movimiento/contrato/lista", name="turno_movimiento_contrato_contrato_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $this->request = $request;
        $em = $this->getDoctrine()->getManager();
        $formBotonera = $this->botoneraLista()
            ->add('txtCodigoCliente', TextType::class, ['required' => false, 'data' => $session->get('filtroTurCodigoCliente'), 'attr' => ['class' => 'form-control']])
            ->add('txtNombreCorto', TextType::class, ['required' => false, 'data' => $session->get('filtroTurNombreCliente'), 'attr' => ['class' => 'form-control', 'readonly' => 'reandonly']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']]);
        $formBotonera->handleRequest($request);
        if ($formBotonera->isSubmitted() && $formBotonera->isValid()) {
            if ($formBotonera->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository($this->clase)->parametrosExcel(), "Excel");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {

            }
        }
//        $form = $this->createFormBuilder()
//            ->add('txtCodigoCliente', TextType::class, ['required' => false, 'data' => $session->get('filtroTurCodigoCliente'), 'attr' => ['class' => 'form-control']])
//            ->add('txtNombreCorto', TextType::class, ['required' => false, 'data' => $session->get('filtroTurNombreCliente'), 'attr' => ['class' => 'form-control', 'readonly' => 'reandonly']])
//            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
//            ->getForm();
//        $form->handleRequest($request);
//        if ($form->get('btnFiltrar')->isClicked()) {
//            if ($form->get('txtCodigoCliente')->getData() != '') {
//                $session->set('filtroTurCodigoCliente', $form->get('txtCodigoCliente')->getData());
//                $session->set('filtroTurNombreCliente', $form->get('txtNombreCorto')->getData());
//            } else {
//                $session->set('filtroTurCodigoCliente', null);
//                $session->set('filtroTurNombreCliente', null);
//            }
//        }
        return $this->render('turno/movimiento/contrato/lista.html.twig', [
            'arrDatosLista' => $this->getDatosLista(),
            'formBotonera' => $formBotonera->createView()
        ]);
    }

    /**
     * @Route("/turno/movimiento/contrato/nuevo/{id}", name="turno_movimiento_contrato_contrato_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arContrato = new TurContrato();
        if ($id != '0') {
            $arContrato = $em->getRepository(TurContrato::class)->find($id);
            if (!$arContrato) {
                return $this->redirect($this->generateUrl('turno_administracion_cliente_cliente_lista'));
            }
        }
        $form = $this->createForm(ContratoType::class, $arContrato);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arContrato);
                $em->flush();
                return $this->redirect($this->generateUrl('turno_administracion_cliente_cliente_detalle', ['id' => $arContrato->getCodigoContratoPk()]));
            }
        }
        return $this->render('turno/administracion/cliente/nuevo.html.twig', [
            'arContrato' => $arContrato,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/turno/administracion/cliente/detalle/{id}", name="turno_administracion_cliente_cliente_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCliente = $em->getRepository(TurCliente::class)->find($id);
        return $this->render('turno/administracion/cliente/detalle.html.twig', array(
            'arCliente' => $arCliente
        ));
    }

}

