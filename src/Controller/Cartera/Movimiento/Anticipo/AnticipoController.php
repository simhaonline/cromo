<?php

namespace App\Controller\Cartera\Movimiento\Anticipo;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Cartera\CarAnticipo;
use App\Entity\Cartera\CarAnticipoConcepto;
use App\Entity\Cartera\CarAnticipoDetalle;
use App\Entity\Cartera\CarCliente;
use App\Entity\Cartera\CarCuentaCobrar;
use App\Entity\Financiero\FinTercero;
use App\Form\Type\Cartera\AnticipoDetalleType;
use App\Form\Type\Cartera\AnticipoType;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AnticipoController extends ControllerListenerGeneral
{
    protected $clase = CarAnticipo::class;
    protected $claseNombre = "CarAnticipo";
    protected $modulo = "Cartera";
    protected $funcion = "Movimiento";
    protected $grupo = "Anticipo";
    protected $nombre = "Anticipo";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/cartera/movimiento/anticipo/anticipo/lista", name="cartera_movimiento_anticipo_anticipo_lista")
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
                General::get()->setExportar($em->createQuery($datos['queryBuilder'])->execute(), "Recibos");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(CarAnticipo::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('cartera_movimiento_anticipo_anticipo_lista'));
            }
        }
        return $this->render('cartera/movimiento/anticipo/anticipo/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
        ]);
    }

    /**
     * @Route("/cartera/movimiento/anticipo/anticipo/nuevo/{id}", name="cartera_movimiento_anticipo_anticipo_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arAnticipo = new CarAnticipo();
        if ($id != '0') {
            $arAnticipo = $em->getRepository(CarAnticipo::class)->find($id);
            if (!$arAnticipo) {
                return $this->redirect($this->generateUrl('cartera_movimiento_anticipo_anticipo_lista'));
            }
        } else {
            $arAnticipo->setFechaPago(new \DateTime('now'));
            $arAnticipo->setUsuario($this->getUser()->getUserName());
        }
        $form = $this->createForm(AnticipoType::class, $arAnticipo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $txtCodigoCliente = $request->request->get('txtCodigoCliente');
                if ($txtCodigoCliente != '') {
                    $arCliente = $em->getRepository(CarCliente::class)->find($txtCodigoCliente);
                    if ($arCliente) {
                        if ($id == 0) {
                            $arAnticipo->setFecha(new \DateTime('now'));
                        }
                        $arAnticipo->setClienteRel($arCliente);
                        $em->persist($arAnticipo);
                        $em->flush();
                        return $this->redirect($this->generateUrl('cartera_movimiento_anticipo_anticipo_detalle', ['id' => $arAnticipo->getCodigoAnticipoPk()]));
                    }
                }

            }
        }
        return $this->render('cartera/movimiento/anticipo/anticipo/nuevo.html.twig', [
            'arAnticipo' => $arAnticipo,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/cartera/movimiento/anticipo/anticipo/detalle/{id}", name="cartera_movimiento_anticipo_anticipo_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $arAnticipo = $em->getRepository(CarAnticipo::class)->find($id);
        $form = Estandares::botonera($arAnticipo->getEstadoAutorizado(), $arAnticipo->getEstadoAprobado(), $arAnticipo->getEstadoAnulado());
        $arrBtnActualizarDetalle = ['label' => 'Actualizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnEliminar = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        if ($arAnticipo->getEstadoAutorizado()) {
            $arrBtnActualizarDetalle['disabled'] = true;
            $arrBtnEliminar['disabled'] = true;
        }
        $form->add('btnActualizarDetalle', SubmitType::class, $arrBtnActualizarDetalle);
        $form->add('btnEliminar', SubmitType::class, $arrBtnEliminar);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrControles = $request->request->all();
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(CarAnticipo::class)->autorizar($arAnticipo);
                $em->getRepository(CarAnticipo::class)->liquidar($arAnticipo);
                return $this->redirect($this->generateUrl('cartera_movimiento_anticipo_anticipo_detalle', array('id' => $id)));
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(CarAnticipo::class)->desautorizar($arAnticipo);
                return $this->redirect($this->generateUrl('cartera_movimiento_anticipo_anticipo_detalle', array('id' => $id)));
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(CarAnticipo::class)->aprobar($arAnticipo);
                return $this->redirect($this->generateUrl('cartera_movimiento_anticipo_anticipo_detalle', array('id' => $id)));
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(CarAnticipoDetalle::class)->eliminar($arAnticipo, $arrDetallesSeleccionados);
                $em->getRepository(CarAnticipo::class)->liquidar($id);
            }
        }

        $arAnticipoDetalles = $paginator->paginate($em->getRepository(CarAnticipoDetalle::class)->lista($id), $request->query->getInt('page', 1), 70);
        return $this->render('cartera/movimiento/anticipo/anticipo/detalle.html.twig', array(
            'arAnticipo' => $arAnticipo,
            'arAnticipoDetalles' => $arAnticipoDetalles,
            'clase' => array('clase' => 'CarAnticipo', 'codigo' => $id),
            'form' => $form->createView()
        ));
    }

    /**
     * @param Request $request
     * @param $codigoAnticipo
     * @param $id
     * @return Response
     * @Route("/cartera/movimiento/anticipo/anticipo/detalle/nuevo/{codigoAnticipo}/{id}", name="cartera_movimiento_anticipo_anticipo_detalle_nuevo")
     */
    public function detalleNuevo(Request $request, $codigoAnticipo, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arAnticipoDetalle = new CarAnticipoDetalle();
        $arAnticipo = $em->getRepository(CarAnticipo::class)->find($codigoAnticipo);
        if ($id != 0) {
            $arAnticipoDetalle = $em->getRepository(CarAnticipoDetalle::class)->find($id);
        }
        $form = $this->createFormBuilder()
            ->add('anticipoConceptoRel', EntityType::class, [
                'class' => 'App\Entity\Cartera\CarAnticipoConcepto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ac')
                        ->orderBy('ac.nombre');
                },
                'choice_label' => 'nombre',
                'required' => true,
                'data' => $arAnticipoDetalle->getAnticipoConceptoRel()
            ])
            ->add('vrPago', IntegerType::class, array('required' => true, 'data' => $arAnticipoDetalle->getVrPago()))
            ->add('guardar', SubmitType::class, array('label' => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $anticipoConceptoRel = $form->get('anticipoConceptoRel')->getData();
            if ($form->get('guardar')->isClicked()) {
                $arAnticipoDetalle->setAnticipoConceptoRel($anticipoConceptoRel);
                $arAnticipoDetalle->setVrPago($form->get('vrPago')->getData());
                $arAnticipoDetalle->setAnticipoRel($arAnticipo);
                $em->persist($arAnticipoDetalle);
                $em->flush();
                $em->getRepository(CarAnticipo::class)->liquidar($codigoAnticipo);
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('cartera/movimiento/anticipo/anticipo/detalleNuevo.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
