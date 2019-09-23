<?php

namespace App\Controller\RecursoHumano\Movimiento\Financiero;
use App\Entity\RecursoHumano\RhuPagoTipo;
use App\Entity\RecursoHumano\RhuCierre;
use App\Entity\RecursoHumano\RhuCierreDetalle;
use App\Entity\Turno\TurSoporte;
use App\Form\Type\RecursoHumano\CierreType;
use App\Formato\RecursoHumano\Cierre;
use App\Formato\RecursoHumano\ResumenConceptos;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CierreController extends AbstractController
{
    protected $clase = RhuCierre::class;
    protected $claseNombre = "RhuCierre";
    protected $modulo = "RecursoHumano";
    protected $funcion = "movimiento";
    protected $grupo = "Financiero";
    protected $nombre = "Cierre";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/movimiento/financiero/cierre/lista", name="recursohumano_movimiento_financiero_cierre_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
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
                General::get()->setExportar($em->getRepository(RhuCierre::class)->lista($raw), "Cierrees");
            }
        }
        $arCierres = $paginator->paginate($em->getRepository(RhuCierre::class)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/movimiento/financiero/cierre/lista.html.twig', [
            'arCierres' => $arCierres,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/financiero/cierre/nuevo/{id}", name="recursohumano_movimiento_financiero_cierre_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCierre = new RhuCierre();
        if ($id != 0) {
            $arCierre = $em->getRepository($this->clase)->find($id);
            if (!$arCierre) {
                return $this->redirect($this->generateUrl('recursohumano_administracion_recurso_empleado_lista'));
            }
        }
        $form = $this->createForm(CierreType::class, $arCierre);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arCierre);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_movimiento_financiero_cierre_detalle', ['id' => $arCierre->getCodigoCierrePk()]));
            }
        }
        return $this->render('recursohumano/movimiento/financiero/cierre/nuevo.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("recursohumano/movimiento/financiero/cierre/detalle/{id}", name="recursohumano_movimiento_financiero_cierre_detalle")
     */
    public function detalle(Request $request, $id, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $arCierre = $this->clase;
        if ($id != 0) {
            $arCierre = $em->getRepository($this->clase)->find($id);
            if (!$arCierre) {
                return $this->redirect($this->generateUrl('recursohumano_movimiento_financiero_cierre_lista'));
            }
        }

        $form = Estandares::botonera($arCierre->getEstadoAutorizado(), $arCierre->getEstadoAprobado(), $arCierre->getEstadoAnulado());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('btnAutorizar')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $em->getRepository(RhuCierre::class)->autorizar($arCierre, $this->getUser()->getUsername());
                return $this->redirect($this->generateUrl('recursohumano_movimiento_financiero_cierre_detalle', ['id' => $id]));
            }
            if ($form->get('btnAprobar')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $em->getRepository(RhuCierre::class)->aprobar($arCierre);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_financiero_cierre_detalle', ['id' => $id]));
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $em->getRepository(RhuCierre::class)->desautorizar($arCierre);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_financiero_cierre_detalle', ['id' => $id]));
            }
        }
        //$arCierreDetalles = $paginator->paginate($em->getRepository(RhuCierreDetalle::class)->lista($arCierre->getCodigoCierrePk()), $request->query->get('page', 1), 1000);
        return $this->render('recursohumano/movimiento/financiero/cierre/detalle.html.twig', [
            'arCierre' => $arCierre,
            'clase' => array('clase' => 'RhuCierre', 'codigo' => $id),
            'form' => $form->createView(),
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoCierre' => $form->get('codigoCierrePk')->getData(),
            'nombre' => $form->get('nombre')->getData(),
            'fechaDesde' => $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null,
            'fechaHasta' => $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null,
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
            'estadoAprobado' => $form->get('estadoAprobado')->getData(),
            'estadoAnulado' => $form->get('estadoAnulado')->getData(),
        ];

        $arPagoTipo = $form->get('codigoPagoTipoFk')->getData();

        if (is_object($arPagoTipo)) {
            $filtro['pagoTipo'] = $arPagoTipo->getCodigoPagoTipoPk();
        } else {
            $filtro['pagoTipo'] = $arPagoTipo;
        }

        return $filtro;

    }
}