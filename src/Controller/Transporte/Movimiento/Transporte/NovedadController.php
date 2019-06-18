<?php
namespace App\Controller\Transporte\Movimiento\Transporte;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Controller\Estructura\MensajesController;
use App\Entity\Transporte\TteNovedad;
use App\Entity\Transporte\TteNovedadTipo;
use App\General\General;
use App\Utilidades\Estandares;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;

class NovedadController extends ControllerListenerGeneral
{
    protected $class= TteNovedad::class;
    protected $claseNombre = "TteNovedad";
    protected $modulo = "Transporte";
    protected $funcion = "Movimiento";
    protected $grupo = "Transporte";
    protected $nombre = "Novedad";

    /**
     * @Route("/transporte/movimiento/transporte/novedad/lista", name="transporte_movimiento_transporte_novedad_lista")
     */
    public function lista(Request $request)
    {    $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');

        $form = $this->createFormBuilder()
            ->add('txtCodigoCliente', TextType::class, ['required' => false, 'data' => $session->get('filtroNovadadCodigoCliente')])
            ->add('txtNombreCorto', TextType::class, ['required' => false, 'data' => $session->get('filtroNovadadNombreCorto'), 'attr' => ['class' => 'form-control', 'readonly' => 'reandonly']])
            ->add('guiaNumero', TextType::class, ['required' => false, 'data' => $session->get('filtroNormaCodigo')])
            ->add('fechaReporteDesde', DateType::class, ['required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd',
                'data' => $session->get('filtroNovadadFechaReporteDesde') ? date_create($session->get('filtroNovadadFechaReporteDesde')): null])

            ->add('fechaReporteHasta', DateType::class, ['required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd',
                'data' => $session->get('filtroNovadadFechaReporteHasta') ? date_create($session->get('filtroNovadadFechaReporteHasta')): null])
            ->add('codigoNovedadTipo',EntityType::class,[
                'required' => true,
                'class' => TteNovedadTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('n')
                        ->orderBy('n.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'placeholder'=>'Todos',
                'required'=>false
            ])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroNovadadCodigoCliente', $form->get('txtCodigoCliente')->getData());
                if ($session->get('filtroNovadadCodigoCliente') == ''){
                    $session->set('filtroNovadadNombreCorto', '');
                }else{
                    $session->set('filtroNovadadNombreCorto', $form->get('txtNombreCorto')->getData());
                }
                $session->set('filtroNovadadNumeroGuia', $form->get('guiaNumero')->getData());
                $session->set('filtroNovadadFechaReporteDesde',  $form->get('fechaReporteDesde')->getData() ?$form->get('fechaReporteDesde')->getData()->format('Y-m-d'): null);
                $session->set('filtroNovadadFechaReporteHasta',  $form->get('fechaReporteHasta')->getData() ?$form->get('fechaReporteHasta')->getData()->format('Y-m-d'): null);

            }
        }
        $arNovedades = $paginator->paginate($em->getRepository(TteNovedad::class)->lista(), $request->query->getInt('page', 1), 30);
        return $this->render('transporte/movimiento/transporte/novedad/lista.html.twig', [
            'arNovedades'=>$arNovedades,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/transporte/movimiento/transporte/novedad/nuevo/{id}", name="transporte_movimiento_transporte_novedad_nuevo")
     */
    public function nuevo(){
        return $this->redirect($this->generateUrl('transporte_movimiento_transporte_novedad_lista'));
    }


    /**
     * @Route("/transporte/movimiento/transporte/novedad/detalle/{id}", name="transporte_movimiento_transporte_novedad_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arNovedad = $em->getRepository(TteNovedad::class)->find($id);
        return $this->render('transporte/movimiento/transporte/novedad/detalle.html.twig', array(
            'arNovedad' => $arNovedad,
        ));
    }
}

