<?php


namespace App\Controller\Crm\Movimiento\Comercial\Negocio;


use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Crm\CrmNegocio;
use App\Form\Type\Crm\NegocioType;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class NegocioController extends ControllerListenerGeneral
{
    protected $clase = CrmNegocio::class;
    protected $claseFormulario = NegocioType::class;
    protected $claseNombre = "CrmNegocio";
    protected $modulo = "Crm";
    protected $funcion = "movimiento";
    protected $grupo = "comercial";
    protected $nombre = "Negocio";

    /**
     * @Route("/crm/movimiento/negocio/lista", name="crm_movimiento_comercial_negocio_lista")
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
        $datos = $this->getDatosLista();

        // Chart
        $series = array(
            array("name" => "Data Serie Name",    "data" => array(1,2,4,5,6,3,8))
        );
        $ob = new Highchart();
        $ob->chart->renderTo('doughnut');  // The #id of the div where to render the chart
        $ob->title->text('Chart Title');
        $ob->xAxis->title(array('text'  => "Horizontal x title"));
        $ob->yAxis->title(array('text'  => "Vertical y title"));
        $ob->series($series);

        return $this->render('crm/movimiento/comercial/negocio/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
            'chart' => $ob
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/crm/movimiento/negocio/nuevo/{id}", name="crm_movimiento_comercial_negocio_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arNegocio = new CrmNegocio();
        if ($id != 0) {
            $arNegocio = $em->getRepository(CrmNegocio::class)->find($id);
            if (!$arNegocio) {
                return $this->redirect($this->generateUrl('crm_movimiento_comercial_negocio_lista'));
            }
        }
        $form = $this->createForm(NegocioType::class, $arNegocio);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arNegocio->setFecha(new \DateTime('now'));
                $arNegocio = $form->getData();
                $em->persist($arNegocio);
                $em->flush();
                return $this->redirect($this->generateUrl('crm_movimiento_comercial_negocio_detalle', ['id' => $arNegocio->getCodigoNegocioPk()]));
            }
        }
        return $this->render('crm/movimiento/comercial/negocio/nuevo.html.twig', [
            'form' => $form->createView(),
            'arNegocio' => $arNegocio
        ]);
    }


    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/crm/movimiento/negocio/detalle/{id}", name="crm_movimiento_comercial_negocio_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        if ($id != 0) {
            $arNegocio = $em->getRepository(CrmNegocio::class)->find($id);
            if (!$arNegocio) {
                return $this->redirect($this->generateUrl('crm_movimiento_comercial_fase_lista'));
            }
        }
        $arNegocio = $em->getRepository(CrmNegocio::class)->find($id);
        return $this->render('crm/movimiento/comercial/negocio/detalle.html.twig', [
            'arNegocio' => $arNegocio
        ]);
    }
}