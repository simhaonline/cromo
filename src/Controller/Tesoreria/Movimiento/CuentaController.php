<?php


namespace App\Controller\Tesoreria\Movimiento;


use App\Controller\Estructura\ControllerListenerGeneral;
use App\Entity\Tesoreria\TesCuenta;
use App\Entity\Tesoreria\TesCuentaTipo;
use App\Entity\Turno\TurConcepto;
use App\Form\Type\Tesoreria\Cuenta;
use App\Form\Type\Tesoreria\CuentaTipoType;
use App\Form\Type\Tesoreria\CuentaType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class CuentaController  extends ControllerListenerGeneral
{
    protected $clase = TesCuenta::class;
    protected $claseNombre = "TesCuenta";
    protected $modulo = "Tesoreria";
    protected $funcion = "Movimiento";
    protected $grupo = "Cuenta";
    protected $nombre = "Cuenta";
    
    /**
     * @Route("/tesoreria/movimiento/cuenta/lista", name="tesoreria_movimiento_cuenta_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('codigoCuentaPk', IntegerType::class, ['required' => false , 'label' => 'Identificacion:'])
            ->add('numero', IntegerType::class, ['required' => false , 'label' => 'Identificacion:'])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroTesCuentaFechaDesde') ? date_create($session->get('filtroTesCuentaFechaDesde')): null])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false,  'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroTesCuentaFechaHasta') ? date_create($session->get('filtroTesCuentaFechaHasta')): null])
            ->add('cuentaTipo', EntityType::class, [
                'class' => TesCuentaTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ct')
                        ->orderBy('ct.codigoCuentaTipoPK', 'ASC');
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
                $session->set('filtroTesCuentaCodigo', $form->get('codigoCuentaPk')->getData());
                $session->set('filtroTesCuentaNumero', $form->get('numero')->getData());
                $session->set('filtroTesCuentaFechaDesde',  $form->get('fechaDesde')->getData() ?$form->get('fechaDesde')->getData()->format('Y-m-d'): null);
                $session->set('filtroTesCuentaFechaHasta', $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d'): null);
                if ($arCuentaTipo != '') {
                    $session->set('filtroTesCuentaCuentaTipo', $arCuentaTipo->getCodigoCuentaTipoPk());
                } else {
                    $session->set('filtroTesCuentaCuentaTipo', null);
                }
            }

            if ($form->get('btnEliminar')->isClicked()) {
                $arData = $request->request->get('ChkSeleccionar');
                $this->get("UtilidadesModelo")->eliminar(TesCuenta::class, $arData);
            }


        }
        $arCuentas = $paginator->paginate($em->getRepository(TesCuenta::class)->lista(), $request->query->getInt('page', 1), 30);
        return $this->render('tesoreria/movimiento/cuenta/lista.html.twig', [
            'arCuentas' => $arCuentas,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/tesoreria/movimiento/cuenta/nuevo/{id}", name="tesoreria_movimiento_cuenta_nuevo")
     */
    public function nuevo(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $arCuenta = new TesCuenta();
        if ($id != 0) {
            $arCuenta = $em->getRepository(TesCuenta::class)->find($id);
        }else{
            $arCuenta->setFecha(new \DateTime('now'));
        }
        $form = $this->createForm(CuentaType::class, $arCuenta);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arCuenta);
                $em->flush();
                return $this->redirect($this->generateUrl('tesoreria_movimiento_cuenta_detalle', array('id' => $arCuenta->getCodigoCuentaPk())));
            }
        }
        return $this->render('tesoreria/movimiento/cuenta/nuevo.html.twig', [
            'arCuenta' => $arCuenta,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/tesoreria/movimiento/cuenta/detalle/{id}", name="tesoreria_movimiento_cuenta_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        if ($id != 0) {
            $arCuenta = $em->getRepository(TesCuenta::class)->find($id);
            if (!$arCuenta) {
                return $this->redirect($this->generateUrl('tesoreria_movimiento_cuenta_lista'));
            }
        }

        return $this->render('tesoreria/movimiento/cuenta/detalle.html.twig', [
            'arCuenta' => $arCuenta
        ]);
    }

}