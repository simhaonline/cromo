<?php


namespace App\Controller\RecursoHumano\Administracion\Nomina;

use App\Controller\MaestroController;
use App\Entity\RecursoHumano\RhuConcepto;
use App\Entity\RecursoHumano\RhuLiquidacionTipo;
use App\Form\Type\RecursoHumano\LiquidacionTipoType;
use App\General\General;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class LiquidacionTipoController extends MaestroController
{

    public $tipo = "Administracion";
    public $modelo = "RhuLiquidacionTipo";


    protected $clase = RhuLiquidacionTipo::class;
    protected $claseNombre = "RhuLiquidacionTipo";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Administracion";
    protected $grupo = "Nomina";
    protected $nombre = "LiquidacionTipo";



    /**
     * @Route("recursohumano/adminsitracion/nomina/liquidaciontipo/lista", name="recursohumano_administracion_nomina_liquidaciontipo_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoLiquidacionTipoPk', TextType::class, array('required' => false))
            ->add('nombre', TextType::class, array('required' => false))
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnEliminar', SubmitType::class, array('label' => 'Eliminar'))
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
                General::get()->setExportar($em->getRepository(RhuLiquidacionTipo ::class)->lista($raw)->getQuery()->execute(), "liquidación tipos");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(RhuLiquidacionTipo::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('recursohumano_administracion_nomina_liquidaciontipo_lista'));
            }
        }
        $arLiquidacionTipos = $paginator->paginate($em->getRepository(RhuLiquidacionTipo::class)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/administracion/nomina/liquidacionTipo/lista.html.twig', [
            'arLiquidacionTipos' => $arLiquidacionTipos,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("recursohumano/adminsitracion/nomina/liquidaciontipo/nuevo/{id}", name="recursohumano_administracion_nomina_liquidaciontipo_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arLiquidacionTipo = $em->getRepository(RhuLiquidacionTipo::class)->find($id);
        if (is_null($arLiquidacionTipo)){
            $arLiquidacionTipo = new RhuLiquidacionTipo();
        }
        $form = $this->createForm(LiquidacionTipoType::class, $arLiquidacionTipo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arLiquidacionTipo = $form->getData();
                $em->persist($arLiquidacionTipo);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_administracion_nomina_liquidaciontipo_detalle', array('id' => $arLiquidacionTipo->getCodigoLiquidacionTipoPk())));
            }
        }

        return $this->render('recursohumano/administracion/nomina/liquidacionTipo/nuevo.html.twig', [
            'arLiquidacionTipo' => $arLiquidacionTipo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("recursohumano/adminsitracion/nomnina/liquidaciontipo/detalle/{id}", name="recursohumano_administracion_nomina_liquidaciontipo_detalle")
     */
    public function detalle(Request $request, PaginatorInterface $paginator,$id){
        $em = $this->getDoctrine()->getManager();
        $arRegistro = $em->getRepository(RhuLiquidacionTipo::class)->find($id);
        return $this->render('recursohumano/administracion/nomina/liquidacionTipo/detalle.html.twig', [
            'arRegistro' => $arRegistro,
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoLiquidacionTipo' => $form->get('codigoLiquidacionTipoPk')->getData(),
            'nombreLiquidacionTipo' => $form->get('nombre')->getData(),
        ];
        return $filtro;
    }
}