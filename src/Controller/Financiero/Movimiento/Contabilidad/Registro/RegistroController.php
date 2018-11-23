<?php

namespace App\Controller\Financiero\Movimiento\Contabilidad\Registro;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Entity\Financiero\FinAsiento;
use App\Entity\Financiero\FinAsientoDetalle;
use App\Entity\Financiero\FinCuenta;
use App\Entity\Financiero\FinRegistro;
use App\Entity\Financiero\FinTercero;
use App\Form\Type\Financiero\AsientoType;
use App\Form\Type\Financiero\RegistroType;
use App\Formato\Financiero\Asiento;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class RegistroController extends ControllerListenerGeneral
{
    protected $clase = FinRegistro::class;
    protected $claseNombre = "FinRegistro";
    protected $modulo = "Financiero";
    protected $funcion = "Movimiento";
    protected $grupo = "Contabilidad";
    protected $nombre = "Registro";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/financiero/movimiento/contabilidad/registro/lista", name="financiero_movimiento_contabilidad_registro_lista")
     */
    public function lista(Request $request)
    {
        $this->request = $request;
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $formBotonera = BaseController::botoneraLista();
        $formBotonera->handleRequest($request);
        $formFiltro = $this->getFiltroLista();
        $formFiltro->handleRequest($request);
        $datos = $this->getDatosLista();
        if ($formBotonera->isSubmitted() && $formBotonera->isValid()) {
            if ($formBotonera->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository($this->clase)->parametrosExcel(), "Registros");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {

            }
        }
        if ($formFiltro->isSubmitted() && $formFiltro->isValid()) {
            if ($formFiltro->get('btnFiltro')->isClicked()) {
                $session->set($this->claseNombre . '_numero', $formFiltro->get('numero')->getData());
                $session->set($this->claseNombre . '_codigoComprobanteFk', $formFiltro->get('codigoComprobanteFk')->getData() != "" ? $formFiltro->get('codigoComprobanteFk')->getData()->getCodigoComprobantePk() : "");
                $session->set($this->claseNombre . '_filtrarFecha', $formFiltro->get('filtrarFecha')->getData());
                if ($formFiltro->get('filtrarFecha')->getData()) {
                    $session->set($this->claseNombre . '_fechaDesde', $formFiltro->get('fechaDesde')->getData()->format('Y-m-d'));
                    $session->set($this->claseNombre . '_fechaHasta', $formFiltro->get('fechaHasta')->getData()->format('Y-m-d'));
                }
                $datos = $this->getDatosLista();
            }
        }
        return $this->render('financiero/movimiento/contabilidad/registro/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/financiero/movimiento/contabilidad/registro/nuevo/{id}", name="financiero_movimiento_contabilidad_registro_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRegistro = new FinRegistro();
        if ($id != 0) {
            $arRegistro = $em->getRepository($this->clase)->find($id);
        } else {
            $arRegistro->setFecha(new \DateTime('now'));
        }
        $form = $this->createForm(RegistroType::class, $arRegistro);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arRegistro);
                $em->flush();
                return $this->redirect($this->generateUrl('financiero_movimiento_contabilidad_registro_detalle', ['id' => $arRegistro->getCodigoRegistroPk()]));
            }
        }
        return $this->render('financiero/movimiento/contabilidad/registro/nuevo.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/financiero/movimiento/contabilidad/registro/detalle/{id}", name="financiero_movimiento_contabilidad_registro_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRegistro = $em->getRepository(FinRegistro::class)->find($id);
        $form = Estandares::botonera(false, false, false);
        $form->handleRequest($request);
        return $this->render('financiero/movimiento/contabilidad/registro/detalle.html.twig', [
            'arRegistro' => $arRegistro,
            'form' => $form->createView()
        ]);
    }

}