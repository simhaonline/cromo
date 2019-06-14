<?php



namespace App\Controller\Crm\Movimiento\Comercial\Fase;


use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Crm\CrmFase;
use App\Form\Type\Crm\FaseType;
use App\Utilidades\Mensajes;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class FaceController extends ControllerListenerGeneral
{
    protected $clase = CrmFase::class;
    protected $claseFormulario = FaseType::class;
    protected $claseNombre = "CrmFase";
    protected $modulo = "Crm";
    protected $funcion = "movimiento";
    protected $grupo = "comercial";
    protected $nombre = "Fase";
    /**
     * @Route("/crm/movimiento/fase/lista", name="crm_movimiento_comercial_fase_lista")
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
//            if($formFiltro->get('form_btnEliminar')->isClicked()){
//                $arrSeleccionados = $request->request->get('ChkSeleccionar');
//                $this->get("UtilidadesModelo")->eliminar(CrmFase::class, $arrSeleccionados);
//                return $this->redirect($this->generateUrl('crm_movimiento_comercial_fase_lista'));
//            }
        }
        $datos = $this->getDatosLista(true);
        return $this->render('crm/movimiento/comercial/fase/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/crm/movimiento/fase/nuevo/{id}", name="crm_movimiento_comercial_fase_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arFase = new CrmFase();
        if ($id != 0) {
            $arFase = $em->getRepository(CrmFase::class)->find($id);
            if (!$arFase) {
                return $this->redirect($this->generateUrl('crm_movimiento_comercial_fase_lista'));
            }
        }
        $form = $this->createForm(FaseType::class, $arFase);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arFase = $em->getRepository(CrmFase::class)->find($form->get('codigoFasePk')->getData());
                if (!$arFase){
                    $arFase = $form->getData();
                    $em->persist($arFase);
                    $em->flush();
                    return $this->redirect($this->generateUrl('crm_movimiento_control_fase_detalle', ['id' => $arFase->getCodigoFasePk()]));
                }else{
                    Mensajes::error('No se puede registrar el código de la fase ya existe');
                    return $this->redirect($this->generateUrl('crm_movimiento_comercial_fase_lista'));
                }
            }
        }
        return $this->render('crm/movimiento/comercial/fase/nuevo.html.twig', [
            'form' => $form->createView(),
            'arFase' => $arFase
        ]);


    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/crm/movimiento/fase/detalle/{id}", name="crm_movimiento_comercial_fase_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        if ($id != 0) {
            $arFase = $em->getRepository(CrmFase::class)->find($id);
            if (!$arFase) {
                return $this->redirect($this->generateUrl('crm_movimiento_comercial_fase_lista'));
            }
        }
        $arFase = $em->getRepository(CrmFase::class)->find($id);

        return $this->render('crm/movimiento/comercial/fase/detalle.html.twig', [
            'arFase' => $arFase
        ]);
    }
}