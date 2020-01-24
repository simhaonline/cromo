<?php

namespace App\Controller\Financiero\Administracion\Contabilidad;

use App\Controller\BaseController;
use App\Controller\Estructura\FuncionesController;
use App\Controller\MaestroController;
use App\Entity\Financiero\FinAsiento;
use App\Entity\Financiero\FinCuenta;
use App\Form\Type\Financiero\CuentaType;
use App\General\General;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CuentaController extends MaestroController
{


    public $tipo = "administracion";
    public $modelo = "FinCuenta";


    protected $clase= FinCuenta::class;
    protected $claseNombre = "FinCuenta";
    protected $modulo   = "Financiero";
    protected $funcion  = "Administracion";
    protected $grupo    = "Contabilidad";
    protected $nombre   = "Cuenta";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/financiero/administracion/contabilidad/cuenta/lista", name="financiero_administracion_contabilidad_cuenta_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('nombre', TextType::class, array('required' => false))
            ->add('codigoCuentaPk', TextType::class, array('required' => false))
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnEliminar', SubmitType::class, array('label' => 'Eliminar'))
            ->add('btnGenerarEstructura', SubmitType::class, array('label' => 'Generar estructura'))
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->setMethod('GET')
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
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $raw['filtros'] = $this->getFiltros($form);
                General::get()->setExportar($em->getRepository(FinCuenta::class)->lista($raw), "cuentas");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(FinCuenta::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('financiero_administracion_contabilidad_cuenta_lista'));
            }
            if ($form->get('btnGenerarEstructura')->isClicked()) {
                $em->getRepository(FinCuenta::class)->generarEstructura();
                return $this->redirect($this->generateUrl('financiero_administracion_contabilidad_cuenta_lista'));
            }
        }

        $arCuentas = $paginator->paginate($em->getRepository(FinCuenta::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('financiero/administracion/contabilidad/cuenta/lista.html.twig', [
            'arCuentas' => $arCuentas,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/financiero/administracion/contabilidad/cuenta/nuevo/{id}", name="financiero_administracion_contabilidad_cuenta_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCuenta = new FinCuenta();
        if ($id != '0') {
            $arCuenta = $em->getRepository(FinCuenta::class)->find($id);
            if (!$arCuenta) {
                return $this->redirect($this->generateUrl('financiero_administracion_contabilidad_cuenta_lista'));
            }
        }
        $form = $this->createForm(CuentaType::class, $arCuenta);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arCuenta);
                $em->flush();
                return $this->redirect($this->generateUrl('financiero_administracion_contabilidad_cuenta_lista'));
            }
            if($form->get('guardarnuevo')->isClicked()){
                $em->persist($arCuenta);
                $em->flush();
                return $this->redirect($this->generateUrl('financiero_administracion_contabilidad_cuenta_nuevo',['id'=>0]));
            }

        }
        return $this->render('financiero/administracion/contabilidad/cuenta/nuevo.html.twig', [
            'arCuenta' => $arCuenta,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/financiero/administracion/contabilidad/cuenta/detalle/{id}", name="financiero_administracion_contabilidad_cuenta_detalle")
     */
    public function detalle(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $arCuenta = $em->getRepository(FinCuenta::class)->find($id);
        $form = $this->createFormBuilder()
            ->getForm();
        $form->handleRequest($request);

        return $this->render('financiero/administracion/contabilidad/cuenta/detalle.html.twig', array(
            'arCuenta' => $arCuenta,
            'form' => $form->createView()
        ));

    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoCuenta' => $form->get('codigoCuentaPk')->getData(),
            'nombre' => $form->get('nombre')->getData(),
        ];

        return $filtro;

    }
}

