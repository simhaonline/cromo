<?php


namespace App\Controller\Transporte\Administracion\Guia;


use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Controller\MaestroController;
use App\Entity\Transporte\TteZona;
use App\Form\Type\Transporte\ZonaType;
use App\General\General;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\BaseController;


class ZonaController extends MaestroController
{
    public $tipo = "Administracion";
    public $modelo = "TteZona";

    protected $clase = TteZona::class;
    protected $claseFormulario = ZonaType::class;
    protected $claseNombre = "TteZona";
    protected $modulo = "Transporte";
    protected $funcion = "Administracion";
    protected $grupo = "Guia";
    protected $nombre = "Zona";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/transporte/administracion/zona/lista", name="transporte_administracion_guia_zona_lista")
     */
    public function lista(Request $request)
    {
        $this->request = $request;
        $em = $this->getDoctrine()->getManager();
        $formBotonera = MaestroController::botoneraLista();
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
                General::get()->setExportar($em->getRepository(TteZona::class)->lista()->getQuery()->getResult(), "Zona");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arData = $request->request->get('ChkSeleccionar');
                $this->get("UtilidadesModelo")->eliminar(TteZona::class, $arData);
            }
        }
        return $this->render('transporte/administracion/zona/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
        ]);
    }

    /**
     * @Route("/transporte/administracion/zona/detalle/{id}", name="transporte_administracion_guia_zona_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arZona = $em->getRepository(TteZona::class)->find($id);

        return $this->render('transporte/administracion/zona/detalle.html.twig', [
            'arZona' => $arZona
        ]);
    }

    /**
     * @Route("/turno/administracion/zona/nuevo/{id}", name="transporte_administracion_guia_zona_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arZona = new TteZona();
        if ($id != '0') {
            $arZona = $em->getRepository(TteZona::class)->find($id);
            if (!$arZona) {
                return $this->redirect($this->generateUrl('turno_administracion_cliente_puesto_lista'));
            }
        }
        $form = $this->createForm(ZonaType::class, $arZona);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arZona);
                $em->flush();
                return $this->redirect($this->generateUrl('transporte_administracion_guia_zona_detalle', ['id' => $arZona->getCodigoZonaPk()]));
            }
        }
        return $this->render('transporte/administracion/zona/nuevo.html.twig', [
            'form' => $form->createView()
        ]);
    }


}