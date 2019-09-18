<?php

namespace App\Controller\Transporte\Movimiento\Financiero;

use App\Controller\Estructura\FuncionesController;
use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteIntermediacion;
use App\Entity\Transporte\TteIntermediacionCompra;
use App\Entity\Transporte\TteIntermediacionDetalle;
use App\Entity\Transporte\TteIntermediacionRecogida;
use App\Entity\Transporte\TteIntermediacionVenta;
use App\Entity\TteGuia;
use App\Form\Type\Transporte\IntermediacionType;
use App\General\General;
use App\Utilidades\Estandares;
use Knp\Component\Pager\PaginatorInterface;
use PhpParser\Node\Stmt\Return_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class IntermediacionController extends AbstractController
{
    protected $clase = TteIntermediacion::class;
    protected $claseNombre = "TteIntermediacion";
    protected $modulo = "Transporte";
    protected $funcion = "Movimiento";
    protected $grupo = "Financiero";
    protected $nombre = "Intermediacion";

   /**
    * @Route("/transporte/movimiento/financiero/intermediacion", name="transporte_movimiento_financiero_intermediacion_lista")
    */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('anio', TextType::class, ['required' => false])
            ->add('mes', ChoiceType::class, [
                'choices' => array(
                    'Enero' => '1', 'Febrero' => '2', 'Marzo' => '3', 'Abril' => '4', 'Mayo' => '5', 'Junio' => '6', 'Julio' => '7',
                    'Agosto' => '8', 'Septiembre' => '9', 'Octubre' => '10', 'Noviembre' => '11', 'Diciembre' => '12',
                ),
                'required'    => false,
                'placeholder' => 'TODOS',
            ])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add( 'btnExcel', SubmitType::class, ['label'=>'Excel', 'attr'=>['class'=> 'btn btn-sm btn-default']])
            ->add( 'btnEliminar', SubmitType::class, ['label'=>'Eliminar', 'attr'=>['class'=> 'btn btn-sm btn-danger']])
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
                $raw['filtros'] = $this->getFiltros($form);
                General::get()->setExportar($em->getRepository(TteIntermediacion::class)->lista($raw)->getQuery()->execute(), "intermediacion");
            }
            if ($form->get('btnEliminar')->isClicked()){
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $this->get("UtilidadesModelo")->eliminar(TteIntermediacion::class, $arrSeleccionados);
                return $this->redirect($this->generateUrl('transporte_movimiento_financiero_intermediacion_lista'));
            }
        }
        $arIntermediacions = $paginator->paginate($em->getRepository(TteIntermediacion::class)->lista($raw), $request->query->getInt('page', 1), 10);

        return $this->render('transporte/movimiento/financiero/intermediacion/lista.html.twig', [
            'arIntermediacions' => $arIntermediacions,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/financiero/intermediacion/nuevo/{id}", name="transporte_movimiento_financiero_intermediacion_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arIntermediacion = new TteIntermediacion();
        if($id != 0) {
            $arIntermediacion = $em->getRepository(TteIntermediacion::class)->find($id);
        }else{
            $arIntermediacion->setAnio((new \DateTime('now'))->format('Y'));
            $arIntermediacion->setMes((new \DateTime('now'))->format('m'));
        }
        $form = $this->createForm(IntermediacionType::class, $arIntermediacion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ultimoDia = date("d", (mktime(0, 0, 0, $arIntermediacion->getMes() + 1, 1, $arIntermediacion->getAnio()) - 1));
            $fecha = date_create($arIntermediacion->getAnio() . "-" . $arIntermediacion->getMes() . "-" . $ultimoDia);
            $arIntermediacion->setFecha($fecha);
            $em->persist($arIntermediacion);
            $em->flush();
            return $this->redirect($this->generateUrl('transporte_movimiento_financiero_intermediacion_detalle', array('id'=> $arIntermediacion->getCodigoIntermediacionPk())));

        }
        return $this->render('transporte/movimiento/financiero/intermediacion/nuevo.html.twig', [
            'arIntermediacion' => $arIntermediacion,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/financiero/intermediacion/detalle/{id}", name="transporte_movimiento_financiero_intermediacion_detalle")
     */
    public function detalle(Request $request, PaginatorInterface $paginator,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $arIntermediacion = $em->getRepository(TteIntermediacion::class)->find($id);

        $form = Estandares::botonera($arIntermediacion->getEstadoAutorizado(),$arIntermediacion->getEstadoAprobado(), $arIntermediacion->getEstadoAnulado());
        $form->add('btnExcel', SubmitType::class, array('label' => 'Excel'));
        $form->add('btnExcelCompra', SubmitType::class, array('label' => 'Excel'));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(TteIntermediacion::class)->autorizar($arIntermediacion);
                return $this->redirect($this->generateUrl('transporte_movimiento_financiero_intermediacion_detalle', ['id' => $id]));
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(TteIntermediacion::class)->desAutorizar($arIntermediacion);
                return $this->redirect($this->generateUrl('transporte_movimiento_financiero_intermediacion_detalle', ['id' => $id]));
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(TteIntermediacion::class)->aprobar($arIntermediacion);
                return $this->redirect($this->generateUrl('transporte_movimiento_financiero_intermediacion_detalle', ['id' => $id]));
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(TteIntermediacionVenta::class)->detalle($id), "IntermediacionVenta");
            }
            if ($form->get('btnExcelCompra')->isClicked()) {
                General::get()->setExportar($em->getRepository(TteIntermediacionCompra::class)->detalle($id), "IntermediacionCompra");
            }
        }
        $arIntermediacionVentas = $this->getDoctrine()->getRepository(TteIntermediacionVenta::class)->detalle($id);
        $arIntermediacionCompras = $this->getDoctrine()->getRepository(TteIntermediacionCompra::class)->detalle($id);
        $arIntermediacionRecogidas = $this->getDoctrine()->getRepository(TteIntermediacionRecogida::class)->detalle($id);
        return $this->render('transporte/movimiento/financiero/intermediacion/detalle.html.twig', [
            'arIntermediacion' => $arIntermediacion,
            'arIntermediacionVentas' => $arIntermediacionVentas,
            'arIntermediacionCompras' => $arIntermediacionCompras,
            'arIntermediacionRecogidas' => $arIntermediacionRecogidas,
            'clase' => array('clase' => 'TteIntermediacion', 'codigo' => $id),
            'form' => $form->createView()]);
    }

    public function getFiltros($form)
    {
        Return $filtros =[
            'anio'=> $form->get('anio')->getData(),
            'mes' => $form->get('mes')->getData()
        ];


    }

}

