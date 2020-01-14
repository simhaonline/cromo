<?php

namespace App\Controller\RecursoHumano\Movimiento\Financiero;

use App\Entity\RecursoHumano\RhuCierreAnio;
use App\Entity\RecursoHumano\RhuCosto;
use App\Entity\RecursoHumano\RhuPagoTipo;
use App\Entity\RecursoHumano\RhuCierre;
use App\Entity\Turno\TurSoporte;
use App\Form\Type\RecursoHumano\CierreAnioType;
use App\Form\Type\RecursoHumano\CierreType;
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
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CierreAnioController extends AbstractController
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
     * @Route("recursohumano/movimiento/financiero/cierreanio/lista", name="recursohumano_movimiento_financiero_cierreanio_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $session = new Session();
        $raw = [
            'filtros'=> $session->get('filtroCierreAnio')
        ];
        $form = $this->createFormBuilder()
            ->add('estadoAutorizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false, 'data'=>$raw['filtros']['estadoAutorizado'] ])
            ->add('estadoAprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false, 'data'=>$raw['filtros']['estadoAprobado'] ])
            ->add('estadoAnulado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false, 'data'=>$raw['filtros']['estadoAnulado'] ])
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        $raw['limiteRegistros'] = $form->get('limiteRegistros')->getData();
        if ($form->isSubmitted()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }
            if ($form->get('btnExcel')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
                General::get()->setExportar($em->getRepository(RhuCierreAnio::class)->lista($raw), "Cierres");
            }
        }
        $arCierresAnio = $paginator->paginate($em->getRepository(RhuCierreAnio::class)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/movimiento/financiero/cierreanio/lista.html.twig', [
            'arCierresAnio' => $arCierresAnio,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/financiero/cierreanio/nuevo/{id}", name="recursohumano_movimiento_financiero_cierreanio_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCierreAnio = new RhuCierreAnio();
        if ($id != 0) {
            $arCierreAnio = $em->getRepository($this->clase)->find($id);
            if (!$arCierreAnio) {
                return $this->redirect($this->generateUrl('recursohumano_movimiento_financiero_cierreanio_lista'));
            }
        }
        $form = $this->createForm(CierreAnioType::class, $arCierreAnio);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arCierreAnio);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_movimiento_financiero_cierreanio_detalle', ['id' => $arCierreAnio->getCodigoCierreAnioPk()]));
            }
        }
        return $this->render('recursohumano/movimiento/financiero/cierreanio/nuevo.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @param PaginatorInterface $paginator
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("recursohumano/movimiento/financiero/cierreanio/detalle/{id}", name="recursohumano_movimiento_financiero_cierreanio_detalle")
     */
    public function detalle(Request $request, $id, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $arCierreAnio = $em->getRepository(RhuCierreAnio::class)->find($id);
        $form = Estandares::botonera($arCierreAnio->getEstadoAutorizado(), $arCierreAnio->getEstadoAprobado(), $arCierreAnio->getEstadoAnulado());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('btnAutorizar')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $em->getRepository(RhuCierreAnio::class)->autorizar($arCierreAnio, $this->getUser()->getUsername());
                return $this->redirect($this->generateUrl('recursohumano_movimiento_financiero_cierreanio_detalle', ['id' => $id]));
            }
            if ($form->get('btnAprobar')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $em->getRepository(RhuCierreAnio::class)->aprobar($arCierreAnio);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_financiero_cierreanio_detalle', ['id' => $id]));
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $em->getRepository(RhuCierreAnio::class)->desautorizar($arCierreAnio);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_financiero_cierreanio_detalle', ['id' => $id]));
            }
        }
        return $this->render('recursohumano/movimiento/financiero/cierreanio/detalle.html.twig', [
            'arCierreAnio' => $arCierreAnio,
            'clase' => array('clase' => 'RhuCierreAnio', 'codigo' => $id),
            'form' => $form->createView(),
        ]);
    }

    public function getFiltros($form)
    {
        $session = new Session();
        $filtro = [
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
            'estadoAprobado' => $form->get('estadoAprobado')->getData(),
            'estadoAnulado' => $form->get('estadoAnulado')->getData(),
        ];

        $session->set('filtroCierreAnio', $filtro);
        return $filtro;

    }
}