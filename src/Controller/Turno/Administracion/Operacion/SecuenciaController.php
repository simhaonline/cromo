<?php


namespace App\Controller\Turno\Administracion\Operacion;


use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\MaestroController;
use App\Entity\Turno\TurSecuencia;
use App\Entity\Turno\TurTurno;
use App\Form\Type\Turno\TurnoType;
use App\General\General;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Type\Turno\SecuenciaType;
use Symfony\Component\HttpFoundation\Request;

class SecuenciaController  extends MaestroController
{
    public $tipo = "Administracion";
    public $modelo = "TurSecuencia";

    protected $clase = TurSecuencia::class;
    protected $claseFormulario = SecuenciaType::class;
    protected $claseNombre = "TurSecuencia";
    protected $modulo = "Turno";
    protected $funcion = "Administracion";
    protected $grupo = "Operacion";
    protected $nombre = "Secuencia";

    /**
     * @Route("/turno/administracion/operacion/secuencia/lista", name="turno_administracion_operacion_secuencia_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $this->request = $request;
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtSecuencia', TextType::class, ['required' => false, 'data' => $session->get('filtroTurSecuenciaCodigoSecuencia')])
            ->add('txtNombre', TextType::class, ['required' => false, 'data' => $session->get('filtroTurSecuenciaNombre')])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn btn-sm btn-danger']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroTurSecuenciaCodigoSecuencia', $form->get('txtSecuencia')->getData());
                $session->set('filtroTurSecuenciaNombre', $form->get('txtNombre')->getData());
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $this->get("UtilidadesModelo")->eliminar(TurSecuencia::class, $arrSeleccionados);
                return $this->redirect($this->generateUrl('turno_administracion_operacion_secuencia_lista'));
            }
            if ($form->get('btnExcel')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                General::get()->setExportar($em->getRepository(TurSecuencia::class)->lista(), "Secuencia");
            }
        }
        $arSecuencias = $paginator->paginate($em->getRepository(TurSecuencia::class)->lista(), $request->query->getInt('page', 1), 30);
        return $this->render('turno/administracion/operacion/secuencia/lista.html.twig', [
            'arSecuencias' => $arSecuencias,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/turno/administracion/operacion/secuencia/nuevo/{id}", name="turno_administracion_operacion_secuencia_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $arSecuencias = new TurSecuencia();
        if ($id != "") {
            $arSecuencias = $em->getRepository(TurSecuencia::class)->find($id);
        }
        $form = $this->createForm(SecuenciaType::class, $arSecuencias);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arSecuencias = $form->getData();
                $em->persist($arSecuencias);
                $em->flush();
                return $this->redirect($this->generateUrl('turno_administracion_operacion_secuencia_detalle', ['id' => $arSecuencias->getCodigoSecuenciaPk()]));
            }
        }
        return $this->render('turno/administracion/operacion/secuencia/nuevo.html.twig', [
            'form' => $form->createView(),
            'arSecuencias' => $arSecuencias
        ]);
    }

    /**
     * @Route("/turno/administracion/operacion/secuencia/detalle/{id}", name="turno_administracion_operacion_secuencia_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        if ($id != "") {
            $arSecuencia = $em->getRepository(TurSecuencia::class)->find($id);
            if (!$arSecuencia) {
                return $this->redirect($this->generateUrl('turno_administracion_operacion_turno_lista'));
            }
        }
        $arSecuencia = $em->getRepository(TurSecuencia::class)->find($id);
        return $this->render('turno/administracion/operacion/secuencia/detalle.html.twig', [
            'arSecuencia' => $arSecuencia
        ]);
    }
}