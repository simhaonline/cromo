<?php

namespace App\Controller\RecursoHumano\Movimiento\Seleccion\Seleccion;


use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\RecursoHumano\RhuSeleccion;
use App\Form\Type\RecursoHumano\SeleccionType;
use App\General\General;
use App\Utilidades\Estandares;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class SeleccionController extends AbstractController
{
    protected $clase = RhuSeleccion::class;
    protected $claseFormulario = SeleccionType::class;
    protected $claseNombre = "RhuSeleccion";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Movimiento";
    protected $grupo = "Seleccion";
    protected $nombre = "Seleccion";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/movimiento/seleccion/seleccion/lista", name="recursohumano_movimiento_seleccion_seleccion_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoSeleccionPk', TextType::class, array('required' => false))
            ->add('nombre', TextType::class, ['required' => false])
            ->add('estadoAutorizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAnulado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']])
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
                $raw['filtros'] = $this->getFiltros($form);
                General::get()->setExportar($em->getRepository(RhuSeleccion::class)->lista($raw), "Selecciones");
            }
        }
        $arSelecciones = $paginator->paginate($em->getRepository(RhuSeleccion::class)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/movimiento/seleccion/seleccion/lista.html.twig', [
            'arSelecciones' => $arSelecciones,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("recursohumano/movimiento/seleccion/seleccion/nuevo/{id}", name="recursohumano_movimiento_seleccion_seleccion_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arSeleccion = new RhuSeleccion();
        if ($id != 0) {
            $arSeleccion = $em->getRepository('App:RecursoHumano\RhuSeleccion')->find($id);
            if (!$arSeleccion) {
                return $this->redirect($this->generateUrl('admin_lista', ['modulo' => 'recursoHumano', 'entidad' => 'seleccion']));
            }
        }
        $form = $this->createForm(SeleccionType::class, $arSeleccion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arSeleccion->setFecha(new \DateTime('now'));
                $em->persist($arSeleccion);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_movimiento_seleccion_seleccion_detalle', ['id' => $arSeleccion->getCodigoSeleccionPk()]));
            }
            /*if ($form->get('guardarnuevo')->isClicked()) {
                $em->persist($arSeleccion);
                $em->flush($arSeleccion);
                return $this->redirect($this->generateUrl('recursoHumano_movimiento_seleccion_seleccion_nuevo', ['codigoSeleccion' => 0]));
            }*/
        }
        return $this->render('recursohumano/movimiento/seleccion/seleccion/nuevo.html.twig', [
            'form' => $form->createView(), 'arSeleccion' => $arSeleccion
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/seleccion/seleccion/detalle/{id}", name="recursohumano_movimiento_seleccion_seleccion_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arSeleccionado = $em->getRepository(RhuSeleccion::class)->find($id);
        if ($id != 0) {
            if (!$arSeleccionado) {
                return $this->redirect($this->generateUrl('crm_administracion_fase_fase_lista'));
            }
        }
        $form = Estandares::botonera($arSeleccionado->getEstadoAutorizado(), $arSeleccionado->getEstadoAprobado(), $arSeleccionado->getEstadoAnulado());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(RhuSeleccion::class)->autorizar($arSeleccionado);
            }
            if ($form->get('btnAnular')->isClicked()) {
                $em->getRepository(RhuSeleccion::class)->anular($arSeleccionado);
            }
        }
        return $this->render('recursohumano/movimiento/seleccion/seleccion/detalle.html.twig', [
            'arSeleccionado' => $arSeleccionado,
            'form' => $form->createView()
        ]);

    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoSeleccion' => $form->get('codigoSeleccionPk')->getData(),
            'nombre' => $form->get('nombre')->getData(),
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
            'estadoAprobado' => $form->get('estadoAprobado')->getData(),
            'estadoAnulado' => $form->get('estadoAnulado')->getData(),
        ];

        return $filtro;

    }

}

