<?php


namespace App\Controller\RecursoHumano\Administracion\Nomina;


use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Controller\MaestroController;
use App\Entity\Financiero\FinCuenta;
use App\Entity\RecursoHumano\RhuConcepto;
use App\Entity\RecursoHumano\RhuConceptoCuenta;
use App\Entity\RecursoHumano\RhuPagoTipo;
use App\Form\Type\RecursoHumano\ConceptoCuentaType;
use App\Form\Type\RecursoHumano\ConceptoType;
use App\Form\Type\RecursoHumano\PagoTipoType;
use App\General\General;
use App\Utilidades\Mensajes;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PagoTipoController extends MaestroController
{
    public $tipo = "administracion";
    public $modelo = "RhuPagoTipo";


    protected $clase = RhuConcepto::class;
    protected $claseNombre = "RhuPagoTipo";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Administracion";
    protected $grupo = "Nomina";
    protected $nombre = "PagoTipo";

    /**
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/adminsitracion/nomina/pagotipo/lista", name="recursohumano_administracion_nomina_pagotipo_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoPagoTipoPk', TextType::class, array('required' => false))
            ->add('nombre', TextType::class, array('required' => false))
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn btn-sm btn-danger']])
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        $raw = [
            'limiteRegistros' => $form->get('limiteRegistros')->getData()
        ];
        if ($form->isSubmitted()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }
            if ($form->get('btnExcel')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
                General::get()->setExportar($em->getRepository(RhuPagoTipo::class)->lista($raw), "Pago tipo");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(RhuConcepto::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('recursohumano_administracion_nomina_concepto_lista'));
            }
        }
        $arPagosTipo = $paginator->paginate($em->getRepository(RhuPagoTipo::class)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/administracion/nomina/pagoTipo/lista.html.twig', [
            'arPagosTipo' => $arPagosTipo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("recursohumano/adminsitracion/nomina/pagotipo/nuevo/{id}", name="recursohumano_administracion_nomina_pagotipo_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arPagoTipo = $em->getRepository(RhuPagoTipo::class)->find($id);
        if ($id != 0) {
            if (gettype($arPagoTipo) == null) {
                $arPagoTipo = new RhuPagoTipo();
            }
        }
        $form = $this->createForm(PagoTipoType::class, $arPagoTipo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arConcepto = $form->getData();
                $em->persist($arConcepto);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_administracion_nomina_pagotipo_lista'));
            }
        }
        return $this->render('recursohumano/administracion/nomina/pagoTipo/nuevo.html.twig', [
            'form' => $form->createView(),
            'arPagoTipo' => $arPagoTipo
        ]);
    }

    /**
     * @Route("recursohumano/adminsitracion/nomnina/pagotipo/detalle/{id}", name="recursohumano_administracion_nomina_pagotipo_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

        }
        $arConcepto = $em->getRepository(RhuConcepto::class)->find($id);
        $arConceptoCuentas = $em->getRepository(RhuConceptoCuenta::class)->findBy(['codigoConceptoFk' => $id]);
        return $this->render('recursohumano/administracion/nomina/concepto/detalle.html.twig', [
            'arConcepto' => $arConcepto,
            'arConceptoCuentas' => $arConceptoCuentas,
            'form' => $form->createView()
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoPagoTipo' => $form->get('codigoPagoTipoPk')->getData(),
            'nombre' => $form->get('nombre')->getData(),
        ];

        return $filtro;

    }

}