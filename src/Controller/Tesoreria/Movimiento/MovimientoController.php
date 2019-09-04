<?php


namespace App\Controller\Tesoreria\Movimiento;


use App\Controller\Estructura\ControllerListenerGeneral;
use App\Entity\Tesoreria\TesCuenta;
use App\Entity\Tesoreria\TesCuentaTipo;
use App\Entity\Tesoreria\TesMovimiento;
use App\Entity\Tesoreria\TesMovimientoTipo;
use App\Entity\Turno\TurConcepto;
use App\Form\Type\Tesoreria\Cuenta;
use App\Form\Type\Tesoreria\MovimientoTipoType;
use App\Form\Type\Tesoreria\MovimientoType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class MovimientoController  extends ControllerListenerGeneral
{
    protected $clase = TesMovimiento::class;
    protected $claseNombre = "TesCuenta";
    protected $modulo = "Tesoreria";
    protected $funcion = "Movimiento";
    protected $grupo = "Movimiento";
    protected $nombre = "Movimiento";

    /**
     * @Route("/tesoreria/movimiento/movimiento/lista", name="tesoreria_movimiento_movimiento_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('codigoMovimientoPk', IntegerType::class, ['required' => false , 'label' => 'Identificacion:'])
            ->add('numero', IntegerType::class, ['required' => false , 'label' => 'Identificacion:'])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroTesMovimientoFechaDesde') ? date_create($session->get('filtroTesMovimientoFechaDesde')): null])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false,  'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroTesMovimientoFechaHasta') ? date_create($session->get('filtroTesMovimientoFechaHasta')): null])
            ->add('cuentaTipo', EntityType::class, [
                'class' => TesMovimientoTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('mt')
                        ->orderBy('mt.codigoMovimientoTipoPK', 'ASC');
                },
                'required'=>false,
                'choice_label' => 'nombre',
                'label' => 'Tipo cuenta:',
                'placeholder'=>'TODOS'

            ])
            ->add('btnFiltro', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnEliminar', SubmitType::class, array('label' => 'Eliminar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltro')->isClicked()) {
                $arCuentaTipo = $form->get('cuentaTipo')->getData();
                $session->set('filtroTesMovimientoCodigo', $form->get('codigoMovimientoPk')->getData());
                $session->set('filtroTesMovimientoNumero', $form->get('numero')->getData());
                $session->set('filtroTesMovimientoFechaDesde',  $form->get('fechaDesde')->getData() ?$form->get('fechaDesde')->getData()->format('Y-m-d'): null);
                $session->set('filtroTesMovimientoFechaHasta', $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d'): null);
                if ($arCuentaTipo != '') {
                    $session->set('filtroTesMovimientoMovimientoTipo', $arCuentaTipo->getCodigoMovimientoTipoPk());
                } else {
                    $session->set('filtroTesMovimientoMovimientoTipo', null);
                }
            }

            if ($form->get('btnEliminar')->isClicked()) {
                $arData = $request->request->get('ChkSeleccionar');
                $this->get("UtilidadesModelo")->eliminar(TesMovimiento::class, $arData);
            }


        }
        $arMovimientos = $paginator->paginate($em->getRepository(TesMovimiento::class)->lista(), $request->query->getInt('page', 1), 30);
        return $this->render('tesoreria/movimiento/movimiento/lista.html.twig', [
            'arMovimientos' => $arMovimientos,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/tesoreria/movimiento/movimiento/nuevo/{id}", name="tesoreria_movimiento_movimiento_nuevo")
     */
    public function nuevo(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $arMovimiento = new TesMovimiento();
        if ($id != 0) {
            $arMovimiento = $em->getRepository(TesMovimiento::class)->find($id);
        }else{
            $arMovimiento->setFecha(new \DateTime('now'));
        }
        $form = $this->createForm(MovimientoType::class, $arMovimiento);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arMovimiento);
                $em->flush();
                return $this->redirect($this->generateUrl('tesoreria_movimiento_movimiento_detalle', array('id' => $arMovimiento->getCodigoMovimientoPk())));
            }
        }
        return $this->render('tesoreria/movimiento/movimiento/nuevo.html.twig', [
            'arMovimiento' => $arMovimiento,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/tesoreria/movimiento/movimiento/detalle/{id}", name="tesoreria_movimiento_movimiento_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        if ($id != 0) {
            $arMovimiento = $em->getRepository(TesMovimiento::class)->find($id);
            if (!$arMovimiento) {
                return $this->redirect($this->generateUrl('tesoreria_movimiento_movimiento_lista'));
            }
        }

        return $this->render('tesoreria/movimiento/movimiento/detalle.html.twig', [
            'arMovimiento' => $arMovimiento
        ]);
    }

}