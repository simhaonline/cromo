<?php

namespace App\Controller\Turno\Administracion\Comercial\Cliente;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Controller\MaestroController;
use App\Entity\Turno\TurCliente;
use App\Entity\Turno\TurClienteIca;
use App\Entity\Turno\TurPuesto;
use App\Form\Type\Turno\ClienteIcaType;
use App\Form\Type\Turno\ClienteType;
use App\Form\Type\Turno\PuestoType;
use App\General\General;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

class ClienteController extends MaestroController
{

    public $tipo = "Administracion";
    public $modelo = "TurCliente";

    protected $clase = TurCliente::class;
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
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $this->request = $request;
        $em = $this->getDoctrine()->getManager();
        $session = new Session();
        $form = $this->createFormBuilder()
            ->add('txtCodigoCliente', TextType::class, array('required' => false, 'data' => $session->get('filtroTurClienteCodigo')))
            ->add('txtNumeroIdentificacion', TextType::class, array('required' => false, 'data' => $session->get('filtroTurClienteIdentificacion')))
            ->add('txtNombre', TextType::class, array('required' => false, 'data' => $session->get('filtroTurClienteNombre')))
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn-sm btn btn-danger']])
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn-sm btn btn-default']])
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtro'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroTurClienteCodigo', $form->get('txtCodigoCliente')->getData());
                $session->set('filtroTurClienteIdentificacion', $form->get('txtNumeroIdentificacion')->getData());
                $session->set('filtroTurClienteNombre', $form->get('txtNombre')->getData());
            }
        }
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(TurCliente::class)->lista(), "Clientes");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arData = $request->request->get('ChkSeleccionar');
                $this->get("UtilidadesModelo")->eliminar(TurCliente::class, $arData);
            }
        }
        $arClientes = $paginator->paginate($em->getRepository(TurCliente::class)->lista($this->getUser()), $request->query->getInt('page', 1), 50);
        return $this->render('turno/administracion/comercial/cliente/lista.html.twig', [
            'arClientes' => $arClientes,
            'form' => $form->createView(),
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
    public function detalle(Request $request,  PaginatorInterface $paginator,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCliente = $em->getRepository(TurCliente::class)->find($id);
        $form = $this->createFormBuilder()
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn btn-sm btn-danger']])
            ->add('btnEliminarIca', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn btn-sm btn-danger']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $this->get('UtilidadesModelo')->eliminar(TurPuesto::class, $arrSeleccionados);
                return $this->redirect($this->generateUrl('turno_administracion_comercial_cliente_detalle', ['id' => $id]));
            }
            if ($form->get('btnEliminarIca')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarIca');
                $em->getRepository(TurClienteIca::class)->eliminar($arrSeleccionados);
            }
        }
        $arPuestos = $em->getRepository(TurPuesto::class)->cliente($id);
        $arClientesIca = $paginator->paginate($em->getRepository(TurClienteIca::class)->lista($id), $request->query->getInt('page', 1), 30);

        return $this->render('turno/administracion/comercial/cliente/detalle.html.twig', array(
            'arCliente' => $arCliente,
            'arClientesIca'=>$arClientesIca,
            'arPuestos' => $arPuestos,
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

    /**
     * @Route("/turno/administracion/comercial/cliente/ica/nuevo/{id}/{codigoCliente}", name="turno_administracion_comercial_cliente_ica_nuevo")
     */
    public function nuevoIca(Request $request, $id, $codigoCliente)
    {
        $em = $this->getDoctrine()->getManager();
        $arClienteIca = new TurClienteIca();
        if ($id != 0) {
            $arClienteIca = $em->getRepository(TurClienteIca::class)->find($id);
            if (!$arClienteIca) {
                return $this->redirect($this->generateUrl('turno_administracion_comercial_cliente_ica_lista'));
            }
        }
        $form = $this->createForm(ClienteIcaType::class, $arClienteIca);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arClienteIca = $form->getData();
                $arCliente = $em->getRepository(TurCliente::class)->find($codigoCliente);
                if ($arCliente) {
                    $codigoDane = $arClienteIca->getCiudadRel()->getDepartamentoRel()->getCodigoDane() . '' . $arClienteIca->getCiudadRel()->getCodigoDane();
                    $arClienteIca->setCodigoDane($codigoDane);
                    $arClienteIca->setCodigoInterface($arClienteIca->getItemRel()->getcodigoInterface());
                    $arClienteIca->setClienteRel($arCliente);
                    $em->persist($arClienteIca);
                    $em->flush();
                }else{
                    Mensajes::error("No existe un cliente, por favor intentelo nuevamente.");

                }
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('turno/administracion/comercial/cliente/nuevoIca.html.twig', [
            'arClienteIca' => $arClienteIca,
            'form' => $form->createView()
        ]);
    }
}

