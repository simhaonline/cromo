<?php


namespace App\Controller\Turno\Administracion\Puesto;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Entity\Transporte\TteCliente;
use App\Entity\Turno\TurCliente;
use App\Entity\Turno\TurPuesto;
use App\Form\Type\Turno\PuestoType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PuestoController extends ControllerListenerGeneral
{
    protected $clase= TurPuesto::class;
    protected $claseFormulario = PuestoType::class;
    protected $claseNombre = "TurPuesto";
    protected $modulo = "Turno";
    protected $funcion = "Administracion";
    protected $grupo = "Cliente";
    protected $nombre = "Puesto";
    /**
     * @param Request $request
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/turno/administracion/puesto/lista", name="turno_administracion_cliente_puesto_lista")
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
        return $this->render('turno/administracion/puesto/lista.html.twig', [
            'arrDatosLista' => $this->getDatosLista(),
            'formBotonera'=> $formBotonera->createView(),
            'formFiltro'=>$formFiltro->createView()
        ]);
    }


    /**
     * @Route("/turno/administracion/puesto/detalle/{id}", name="turno_administracion_cliente_puesto_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arPuesto = $em->getRepository(TurPuesto::class)->find($id);
       // dd($arPuesto);
        return $this->render('turno/administracion/puesto/detalle.html.twig', array(
            'arPuesto'=>$arPuesto
        ));
    }

    /**
     * @Route("/turno/administracion/puesto/nuevo/{id}", name="turno_administracion_cliente_puesto_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arTurPuesto = new TurPuesto();
        if ($id != '0') {
            $arTurPuesto = $em->getRepository(TurPuesto::class)->find($id);
            if (!$arTurPuesto) {
                return $this->redirect($this->generateUrl('turno_administracion_cliente_puesto_lista'));
            }
        }
        $form = $this->createForm(PuestoType::class, $arTurPuesto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arTurPuesto);
                $em->flush();
                return $this->redirect($this->generateUrl('turno_administracion_cliente_puesto_detalle', ['id'=>$arTurPuesto->getCodigoPuestoPk()]));
            }
        }
        return $this->render('transporte/administracion/zona/nuevo.html.twig', [
            'arTurno' => $arTurPuesto,
            'form' => $form->createView()
        ]);
    }
}