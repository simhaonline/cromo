<?php

namespace App\Controller\Inventario\Movimiento\Inventario;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Inventario\InvCostoCosto;
use App\Entity\Inventario\InvItem;
use App\Entity\Inventario\InvCosto;
use App\Entity\Inventario\InvCostoDetalle;
use App\Entity\Inventario\InvCostoTipo;
use App\Entity\Inventario\InvTercero;
use App\Form\Type\Inventario\CostoCostoType;
use App\Formato\Inventario\Costo;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Type\Inventario\CostoType;

class CostoController extends AbstractController
{
    protected $class = InvCosto::class;
    protected $claseNombre = "InvCosto";
    protected $modulo = "Inventario";
    protected $funcion = "Movimiento";
    protected $grupo = "Inventario";
    protected $nombre = "Costo";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/inventario/movimiento/inventario/costo/lista", name="inventario_movimiento_inventario_costo_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoCostoPk', TextType::class, array('required' => false))
            ->add('codigoCostoTipoFk', EntityType::class, [
                'class' => InvCostoTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ct')
                        ->orderBy('ct.codigoCostoTipoPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS'
            ])
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
                General::get()->setExportar($em->getRepository(InvCosto::class)->lista($raw), "Costo");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(InvCosto::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('inventario_movimiento_inventario_costo_lista'));
            }
        }
        $arCostos = $paginator->paginate($em->getRepository(InvCosto::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('inventario/movimiento/inventario/costo/lista.html.twig', [
            'arCostos' => $arCostos,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/inventario/movimiento/inventario/costo/nuevo/{id}", name="inventario_movimiento_inventario_costo_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCosto = new InvCosto();
        if ($id != 0) {
            $arCosto = $em->getRepository(InvCosto::class)->find($id);
        }
        $form = $this->createForm(CostoType::class, $arCosto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $txtCodigoTercero = $request->request->get('txtCodigoTercero');
                if ($txtCodigoTercero != '') {
                    $arTercero = $em->getRepository(InvTercero::class)->find($txtCodigoTercero);
                    if ($arTercero) {
                        if ($id == 0) {
                            $arCosto->setUsuario($this->getUser()->getUserName());
                        }
                        $arCosto->setTerceroRel($arTercero);
                        $em->persist($arCosto);
                        $em->flush();
                        return $this->redirect($this->generateUrl('inventario_movimiento_inventario_costo_detalle', ['id' => $arCosto->getCodigoCostoPk()]));
                    }
                }


            }
        }
        return $this->render('inventario/movimiento/inventario/costo/nuevo.html.twig', [
            'form' => $form->createView(),
            'arCosto' => $arCosto
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route("/inventario/movimiento/inventario/costo/detalle/{id}", name="inventario_movimiento_inventario_costo_detalle")
     */
    public function detalle(Request $request,PaginatorInterface $paginator ,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCosto = $em->getRepository(InvCosto::class)->find($id);
        $form = Estandares::botonera($arCosto->getEstadoAutorizado(), $arCosto->getEstadoAprobado(), $arCosto->getEstadoAnulado());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrControles = $request->request->all();
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(InvCosto::class)->autorizar($arCosto);
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(InvCosto::class)->desautorizar($arCosto);
            }
            if ($form->get('btnImprimir')->isClicked()) {
                $objFormatocosto = new Costo();
                $objFormatocosto->Generar($em, $id);
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(InvCosto::class)->aprobar($arCosto);
            }
            if ($form->get('btnAnular')->isClicked()) {
                $em->getRepository(InvCosto::class)->anular($arCosto);
            }
            return $this->redirect($this->generateUrl('inventario_movimiento_inventario_costo_detalle', ['id' => $id]));
        }
        $arCostoDetalles = $paginator->paginate($em->getRepository(InvCostoDetalle::class)->costo($id), $request->query->getInt('page', 1), 1000);
        return $this->render('inventario/movimiento/inventario/costo/detalle.html.twig', [
            'form' => $form->createView(),
            'arCostoDetalles' => $arCostoDetalles,
            'arCosto' => $arCosto,
            'clase' => array('clase'=>'InvCosto', 'codigo' => $id),
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoCosto' => $form->get('codigoCostoPk')->getData(),
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
            'estadoAprobado' => $form->get('estadoAprobado')->getData(),
            'estadoAnulado' => $form->get('estadoAnulado')->getData(),
        ];

        $arCostoTipo = $form->get('codigoCostoTipoFk')->getData();

        if (is_object($arCostoTipo)) {
            $filtro['incidenteTipo'] = $arCostoTipo->getCodigoCostoTipoPk();
        } else {
            $filtro['incidenteTipo'] = $arCostoTipo;
        }

        return $filtro;

    }

}
