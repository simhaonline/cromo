<?php


namespace App\Controller\Turno\Administracion\venta;


use App\Controller\BaseController;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Turno\TurItem;
use App\Form\Type\RecursoHumano\EmpleadoType;
use App\Form\Type\Turno\ItemType;
use App\General\General;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class ItemController extends  AbstractController
{
    /**
     * @Route("/turno/administracion/venta/item/lista", name="turno_administracion_venta_item_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('itemCodigo', TextType::class, array('required' => false))
            ->add('itemNombre', TextType::class, array('required' => false))
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
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(TurItem::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('turno_administracion_venta_item_lista'));
            }
            if ($form->get('btnExcel')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $raw['filtros'] = $this->getFiltros($form);
                General::get()->setExportar($em->getRepository(TurItem::class)->lista($raw), "Items");
            }
        }

        $arItems = $paginator->paginate($em->getRepository(TurItem::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('turno/administracion/comercial/item/lista.html.twig', [
            'arItems' => $arItems,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/turno/administracion/comercial/item/nuevo/{id}", name="turno_administracion_venta_item_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arItem = new TurItem();
        if ($id != 0) {
            $arItem = $em->getRepository(TurItem::class)->find($id);
            if (!$arItem) {
                return $this->redirect($this->generateUrl('turno_administracion_comercial_item_lista'));
            }
        }
        $form = $this->createForm(ItemType::class, $arItem);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arItem = $form->getData();
                $arItem->setPorcentajeIva($arItem->getImpuestoIvaVentaRel()->getPorcentaje());
                $em->persist($arItem);
                $em->flush();
                return $this->redirect($this->generateUrl('turno_administracion_venta_item_detalle', array('id' => $arItem->getCodigoItemPk())));
            } else {
                return $this->redirect($this->generateUrl('turno_administracion_venta_item_lista'));
            }
        }
        return $this->render('turno/administracion/comercial/item/nuevo.html.twig', [
            'form' => $form->createView(),
            'arItem' => $arItem
        ]);
    }


    /**
     * @Route("/turno/administracion/comercial/item/detalle/{id}", name="turno_administracion_venta_item_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arItem = $em->getRepository(TurItem::class)->find($id);
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirect($this->generateUrl('turno_administracion_venta_item_detalle', ['id' => $id]));
        }
        return $this->render('turno/administracion/comercial/item/detalle.html.twig', [
            'form' => $form->createView(),
            'arItem' => $arItem,
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'itemCodigo' => $form->get('itemCodigo')->getData(),
            'itemNombre' => $form->get('itemNombre')->getData(),
        ];


        return $filtro;

    }


}