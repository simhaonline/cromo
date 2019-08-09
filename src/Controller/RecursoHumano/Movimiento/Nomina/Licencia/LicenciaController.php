<?php


namespace App\Controller\RecursoHumano\Movimiento\Nomina\Licencia;


use App\Controller\Estructura\ControllerListenerGeneral;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class LicenciaController extends ControllerListenerGeneral
{
    protected $clase = RhuLicencia::class;
    protected $claseNombre = "RhuLicencia";
    protected $modulo = "RecursoHumano";
    protected $funcion = "movimiento";
    protected $grupo = "Licencia";
    protected $nombre = "Licencia";

    /**
     * @Route("recursohumano/moviento/nomina/licencia/lista", name="recursohumano_movimiento_nomina_licencia_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('codigoEmpleadoPk', TextType::class, array('required' => false))
            ->add('pagoRel', EntityType::class, [
                'class' => RhuPago::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.codigoPagoPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS'
            ])
            ->add('codigoLiquidacionTipo', EntityType::class, [
                'class' => RhuLicenciaTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.codigoLiquidacionTipoPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS'
            ])
            ->add('fechaLicenciaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroRhuLicenciaFechaDesde') ? date_create($session->get('filtroRhuLicenciaFechaDesde')): null])
            ->add('fechaLicenciaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false,  'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroRhuLicenciaFechaHasta') ? date_create($session->get('filtroRhuLicenciaFechaHasta')): null])
            ->add('fechas',      CheckboxType::class, ['required' => false])
            ->getForm();

        if ($form->isSubmitted()) {

        }

        //$arGuias = $paginator->paginate($em->getRepository(RhuLicencia::class)->lista(), $request->query->getInt('page', 1), 30);
        $arLicencias = array();
        return $this->render('recursohumano/movimiento/nomina/licencia/lista.html.twig', [
            'arLicencias' => $arLicencias,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("recursohumano/moviento/nomina/licencia/nuevo/{id}", name="recursohumano_movimiento_nomina_licencia_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arLicencia = new RhuLicencia();
        $form = $this->createForm(RhuLicencia::class, $arLicencia);
        return $this->render('recursohumano/movimiento/nomina/licencia/nuevo.html.twig', [
            'arLicencia' => $arLicencia,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("recursohumano/moviento/nomina/licencia/detalle/{id}", name="recursohumano_movimiento_nomina_licencia_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        if ($id != 0) {
            $arLicencia = $em->getRepository(RhuLicencia::class)->find($id);
            if (!$arLicencia) {
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_licencia_lista'));
            }
        }
        $arLicencia = $em->getRepository(RhuLicencia::class)->find($id);
        return $this->render('recursohumano/movimiento/nomina/licencia/detalle.html.twig', [
            'arContacto' => $arLicencia
        ]);

    }

}