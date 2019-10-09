<?php

namespace App\Controller\Inventario\Administracion;

use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\General\FuncionesGeneralesController;
use App\Entity\Inventario\InvItem;
use App\Form\Type\Inventario\ItemType;
use App\General\General;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ItemController extends AbstractController
{
    var $query = '';
    protected $class= InvItem::class;
    protected $claseNombre = "InvItem";
    protected $modulo = "Inventario";
    protected $funcion = "Administracion";
    protected $grupo = "Inventario";
    protected $nombre = "Item";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/inventario/administracion/inventario/item/lista", name="inventario_administracion_inventario_item_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('nombreItem', TextType::class, ['required' => false])
            ->add('codigoItem', TextType::class, ['required' => false])
            ->add('referenciaItem', TextType::class, ['required' => false])
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
            if($form->get('btnFiltrar')->isClicked()){
                $raw['filtros'] = $this->getFiltros($form);
            }
            if($form->get('btnExcel')->isClicked()){
                $raw['filtros'] = $this->getFiltros($form);
                General::get()->setExportar($this->getDoctrine()->getRepository(InvItem::class)->lista($raw)->getQuery()->execute(), 'Items');
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(InvItem::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('inventario_administracion_inventario_item_lista'));
            }
        }
        $arItems = $paginator->paginate($this->getDoctrine()->getRepository(InvItem::class)->lista($raw), $request->query->getInt('page', 1), 50);
        return $this->render('inventario/administracion/item/lista.html.twig', [
            'arItems' => $arItems,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/inventario/administracion/inventario/item/nuevo/{id}",name="inventario_administracion_inventario_item_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arItem = new InvItem();
        if ($id != 0) {
            $arItem = $em->getRepository(InvItem::class)->find($id);
            if (!$arItem) {
                return $this->redirect($this->generateUrl('inventario_administracion_inventario_item_lista'));
            }
        } else {
            $arItem->setProducto(true);
        }
        $form = $this->createForm(ItemType::class, $arItem);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrControles = $request->request->All();
            if ($form->get('guardar')->isClicked() || $form->get('guardarnuevo')->isClicked()) {
                $arItem->setPorcentajeIva($arItem->getImpuestoIvaVentaRel()->getPorcentaje());
                if (isset($arrControles["tipo"])) {
                    switch ($arrControles["tipo"]) {
                        case 1:
                            $arItem->setProducto(1);
                            $arItem->setServicio(0);
                            break;
                        case 2:
                            $arItem->setServicio(1);
                            $arItem->setProducto(0);
                            break;
                    }
                }
                $em->persist($arItem);
                $em->flush();
                if ($form->get('guardarnuevo')->isClicked()) {
                    return $this->redirect($this->generateUrl('inventario_administracion_inventario_item_nuevo', ['id' => 0]));
                } else {
                    return $this->redirect($this->generateUrl('inventario_administracion_inventario_item_detalle', ['id' => $arItem->getCodigoItemPk()]));
                }
            }
        }
        return $this->render('inventario/administracion/item/nuevo.html.twig', [
            'form' => $form->createView(),
            'arItem' => $arItem
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/inventario/administracion/inventario/item/detalle/{id}",name="inventario_administracion_inventario_item_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arItem = $em->getRepository(InvItem::class)->find($id);
        return $this->render('inventario/administracion/item/detalle.html.twig', [
            'arItem' => $arItem,
            'clase' => array('clase' => 'InvItem', 'codigo' => $id),
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'nombreItem'=> $form->get('nombreItem')->getData(),
            'codigoItem' => $form->get('codigoItem')->getData(),
            'referenciaItem' =>  $form->get('referenciaItem')->getData()
        ];

        return $filtro;

    }
}
