<?php

namespace App\Controller\RecursoHumano\Administracion\Recurso\Contrato;


use App\Controller\BaseController;
use App\Entity\RecursoHumano\RhuContrato;
use App\Form\Type\RecursoHumano\ContratoType;
use App\General\General;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ContratoController extends BaseController
{
    protected $clase = RhuContrato::class;
    protected $claseFormulario = ContratoType::class;
    protected $claseNombre = "RhuContrato";
    protected $modulo = "RecursoHumano";
    protected $funcion = "administracion";
    protected $grupo = "Recurso";
    protected $nombre = "Contrato";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/administracion/recurso/contrato/lista", name="recursohumano_administracion_recurso_contrato_lista")
     */
    public function lista(Request $request)
    {
        $this->request = $request;
        $em = $this->getDoctrine()->getManager();
        $formBotonera = $this->botoneraLista();
        $formBotonera->handleRequest($request);
        if ($formBotonera->isSubmitted() && $formBotonera->isValid()) {
            if ($formBotonera->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository($this->clase)->parametrosExcel(), "Excel");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {

            }
        }
        return $this->render('recursohumano/administracion/recurso/contrato/lista.html.twig', [
            'arrDatosLista' => $this->getDatosLista(),
            'formBotonera' => $formBotonera->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/administracion/recurso/contrato/detalle/{id}", name="recursohumano_administracion_recurso_contrato_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arContrato = new RhuContrato();
        if ($id != 0) {
            $arContrato = $em->getRepository(RhuContrato::class)->find($id);
            if (!$arContrato) {
                return $this->redirect($this->generateUrl('recursohumano_administracion_recurso_contrato_lista'));
            }
        }
        return $this->render('recursohumano/administracion/recurso/contrato/detalle.html.twig', [
            'arContrato' => $arContrato
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/administracion/recurso/contrato/detalle/parametrosIniciales/{id}", name="recursohumano_administracion_recurso_contrato_detalle_parametrosIniciales")
     */
    public function parametrosIniciales(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arContrato = $em->getRepository(RhuContrato::class)->find($id);
        $form = $this->createFormBuilder()
            ->add('fechaUltimoPagoCesantias', DateType::class, ['widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => ['class' => 'date',],'data' => $arContrato->getFechaUltimoPagoCesantias()])
            ->add('fechaUltimoPagoVacaciones', DateType::class, ['widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => ['class' => 'date',],'data' => $arContrato->getFechaUltimoPagoVacaciones()])
            ->add('fechaUltimoPagoPrimas', DateType::class, ['widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => ['class' => 'date',],'data' => $arContrato->getFechaUltimoPagoPrimas()])
            ->add('fechaUltimoPago', DateType::class, ['widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => ['class' => 'date',],'data' => $arContrato->getFechaUltimoPago()])
            ->add('btnGuardar',SubmitType::class,['label' => 'Guardar'])
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if($form->get('btnGuardar')->isClicked()){
                $arContrato->setFechaUltimoPago($form->get('fechaUltimoPago')->getData());
                $arContrato->setFechaUltimoPagoPrimas($form->get('fechaUltimoPagoPrimas')->getData());
                $arContrato->setFechaUltimoPagoVacaciones($form->get('fechaUltimoPagoVacaciones')->getData());
                $arContrato->setFechaUltimoPagoCesantias($form->get('fechaUltimoPagoCesantias')->getData());
                $em->persist($arContrato);
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('recursohumano/administracion/recurso/contrato/parametrosIniciales.html.twig', [
            'arContrato' => $arContrato,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/administracion/recurso/contrato/nuevo/{id}", name="recursohumano_administracion_recurso_contrato_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        return $this->redirect($this->generateUrl('recursohumano_administracion_recurso_contrato_lista'));
    }
}