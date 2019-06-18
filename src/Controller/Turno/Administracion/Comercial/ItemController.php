<?php


namespace App\Controller\Turno\Administracion\Comercial;


use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Turno\TurItem;
use App\Form\Type\RecursoHumano\EmpleadoType;
use App\Form\Type\Turno\ItemType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class ItemController extends ControllerListenerGeneral
{
    protected $clase = TurItem::class;
    protected $claseFormulario = ItemType::class;
    protected $claseNombre = "TurItem";
    protected $modulo = "Turno";
    protected $funcion = "Administracion";
    protected $grupo = "comercial";
    protected $nombre = "Item";

    /**
     * @Route("/turno/administracion/comercial/item/lista", name="turno_administracion_comercial_item_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $this->request = $request;
        $em = $this->getDoctrine()->getManager();
        $formBotonera = BaseController::botoneraLista();
        $formBotonera->handleRequest($request);
        $formFiltro = $this->getFiltroLista();
        $formFiltro->handleRequest($request);
        if ($formFiltro->isSubmitted() && $formFiltro->isValid()) {
            if ($formFiltro->get('btnFiltro')->isClicked()) {
                FuncionesController::generarSession($this->modulo, $this->nombre, $this->claseNombre, $formFiltro);
            }
        }
        if ($formBotonera->isSubmitted() && $formBotonera->isValid()) {
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $this->get("UtilidadesModelo")->eliminar(TurItem::class, $arrSeleccionados);
                return $this->redirect($this->generateUrl('turno_administracion_comercial_item_lista'));
            }
        }
        $datos = $this->getDatosLista(true);
        return $this->render('turno/administracion/comercial/item/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
        ]);
    }

    /**
     * @Route("/turno/administracion/comercial/item/nuevo/{id}", name="turno_administracion_comercial_item_nuevo")
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
                $em->persist($arItem);
                $em->flush();
                return $this->redirect($this->generateUrl('turno_administracion_comercial_item_detalle', array('id' => $arItem->getCodigoItemPk())));
            } else {
                return $this->redirect($this->generateUrl('turno_administracion_comercial_item_lista'));
            }
        }
        return $this->render('turno/administracion/comercial/item/nuevo.html.twig', [
            'form' => $form->createView(),
            'arItem' => $arItem
        ]);
    }


    /**
     * @Route("/turno/administracion/comercial/item/detalle/{id}", name="turno_administracion_comercial_item_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arItem = $em->getRepository(TurItem::class)->find($id);
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirect($this->generateUrl('turno_administracion_comercial_item_detalle', ['id' => $id]));
        }
        return $this->render('turno/administracion/comercial/item/detalle.html.twig', [
            'form' => $form->createView(),
            'arItem' => $arItem,
        ]);
    }


}