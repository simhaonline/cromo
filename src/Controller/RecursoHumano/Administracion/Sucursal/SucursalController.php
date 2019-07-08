<?php


namespace App\Controller\RecursoHumano\Administracion\Sucursal;


use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\RecursoHumano\RhuSucursal;
use App\Form\Type\RecursoHumano\SucursalType;
use App\General\General;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SucursalController extends ControllerListenerGeneral
{
    protected $clase = RhuSucursal::class;
    protected $claseNombre = "RhuSucursal";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Administracion";
    protected $grupo = "Seguridadsocial";
    protected $nombre = "Sucursal";

    /**
     * @Route("recursohumano/adminsitracion/seguridadsocial/sucursal/lista", name="recursohumano_administracion_seguridadsocial_sucursal_lista")
     */
    public function lista(Request $request)
    {
        $this->request = $request;
        $em = $this->getDoctrine()->getManager();
        $formBotonera = $this->botoneraLista();
        $formBotonera->handleRequest($request);
        $formFiltro = $this->getFiltroLista();
        $formFiltro->handleRequest($request);

        if ($formFiltro->isSubmitted() && $formFiltro->isValid()) {
            if ($formFiltro->get('btnFiltro')->isClicked()) {
                FuncionesController::generarSession($this->modulo, $this->nombre, $this->claseNombre, $formFiltro);
            }
        }
        $datos = $this->getDatosLista(true);
        if ($formBotonera->isSubmitted() && $formBotonera->isValid()) {
            if ($formBotonera->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->createQuery($datos['queryBuilder'])->execute(), "Sucursales");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $this->get("UtilidadesModelo")->eliminar(RhuSucursal::class, $arrSeleccionados);
                return $this->redirect($this->generateUrl('recursohumano_administracion_seguridadsocial_sucursal_lista'));
            }
        }
        return $this->render('recursohumano/administracion/seguridadsocial/sucursal/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView()
        ]);
    }

    /**
     * @Route("recursohumano/adminsitracion/seguridadsocial/sucursal/nuevo/{id}", name="recursohumano_administracion_seguridadsocial_sucursal_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arSucursal = $em->getRepository(RhuSucursal::class)->find($id);
        if ($id != 0) {
			if (gettype($arSucursal) == null) {
                $arSucursal = new RhuSucursal();
            }
		}
        $form = $this->createForm(SucursalType::class, $arSucursal);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arSucursal = $form->getData();
                $em->persist($arSucursal);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_administracion_seguridadsocial_sucursal_detalle', ['id' => $arSucursal->getCodigoSucursalPk()]));
            }
        }
        return $this->render('recursohumano/administracion/seguridadsocial/sucursal/nuevo.html.twig', [
            'form' => $form->createView(),
            'arSucursal' => $arSucursal
        ]);
    }

    /**
     * @Route("recursohumano/adminsitracion/seguridadsocial/sucursal/detalle/{id}", name="recursohumano_administracion_seguridadsocial_sucursal_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        if ($id != 0) {
            $arSucursal = $em->getRepository(RhuSucursal::class)->find($id);
            if (!$arSucursal) {
                return $this->redirect($this->generateUrl('recursohumano_administracion_seguridadsocial_sucursal_lista'));
            }
        }
        $arSucursal = $em->getRepository(RhuSucursal::class)->find($id);

        return $this->render('recursohumano/administracion/seguridadsocial/sucursal/detalle.html.twig', [
            'arSucursal' => $arSucursal
        ]);
	}

}