<?php

namespace App\Controller\RecursoHumano\Movimiento\Recurso\Acreditacion;



use App\Entity\RecursoHumano\RhuAcreditacion;
use App\Form\Type\RecursoHumano\AcreditacionType;
use App\General\General;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class AcreditacionController  extends AbstractController
{
    /**
    * @Route("recursohumano/movimiento/recurso/acreditacion/lista", name="recursohumano_movimiento_recurso_acreditacion_lista")
    */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoAcreditacionPk', TextType::class, array('required' => false))
            ->add('codigoEmpleadoFk', TextType::class, array('required' => false))
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false,  'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('estadoAnulado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAutorizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnEliminar', SubmitType::class, array('label' => 'Eliminar'))
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->setMethod('GET')
            ->getForm();
        $form->handleRequest($request);
        $raw = [
            'limiteRegistros' => $form->get('limiteRegistros')->getData()
        ];
        if ($form->isSubmitted()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(RhuAcreditacion::class)->lista($raw)->getQuery()->execute(), "Acreditaciones");

            }
            if ($form->get('btnEliminar')->isClicked()) {

            }
        }
        $arAcreditaciones =$paginator->paginate($em->getRepository(RhuAcreditacion::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('recursohumano/movimiento/recurso/acreditacion/lista.html.twig', [
            'arAcreditaciones' => $arAcreditaciones,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("recursohumano/movimiento/recurso/acreditacion/nuevo/{id}", name="recursohumano_movimiento_recurso_acreditacion_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arAcreditacion = new RhuAcreditacion();
        if ($id != 0) {
            $arAcreditacion = $em->getRepository(RhuAcreditacion::class)->find($id);
        }

        $form = $this->createForm(AcreditacionType::class, $arAcreditacion);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->get('guardar')->isClicked()) {
                $arAcreditacion = $form->getData();
                $em->persist($arAcreditacion);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_movimiento_recurso_acreditacion_detalle', array('id' => $arAcreditacion->getCodigoAcreditacionPk())));
            }
        }
        return $this->render('recursohumano/movimiento/recurso/acreditacion/nuevo.html.twig', [
            'arAcreditacion' => $arAcreditacion,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("recursohumano/movimiento/recurso/acreditacion/detalle/{id}", name="recursohumano_movimiento_recurso_acreditacion_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        if ($id != 0) {
            $arAcreditacion = $em->getRepository(RhuAcreditacion::class)->find($id);
            if (!$arAcreditacion) {
                return $this->redirect($this->generateUrl('recursohumano_movimiento_recurso_acreditacion_lista'));
            }
        }
        $form = $this->createFormBuilder()
            ->add('btnAutorizar', SubmitType::class, array('label' => 'Autorizar'))
            ->add('btnDesautorizar', SubmitType::class, array('label' => 'Desautorizar'))
            ->add('btnImprimir', SubmitType::class, array('label' => 'Imprimir'))
            ->add('btnAprobar', SubmitType::class, array('label' => 'Aprobar'))
            ->add('btnAnular', SubmitType::class, array('label' => 'Anular'))
            ->getForm();
        $form->handleRequest($request);
        $arAcreditacion = $em->getRepository(RhuAcreditacion::class)->find($id);

        return $this->render('recursohumano/movimiento/recurso/acreditacion/detalle.html.twig', [
            'arAcreditacion' => $arAcreditacion,
            'form'=>$form->createView()
        ]);
    }

    public function getFiltros($form)
    {
        return $filtro = [
            'codigoAcreditacionPk' => $form->get('codigoAcreditacionPk')->getData(),
            'fechaIngresoDesde' => $form->get('fechaIngresoDesde')->getData() ?$form->get('fechaIngresoDesde')->getData()->format('Y-m-d'): null,
            'fechaIngresoHasta' => $form->get('fechaIngresoHasta')->getData() ?$form->get('fechaIngresoHasta')->getData()->format('Y-m-d'): null,
        ];
    }
}