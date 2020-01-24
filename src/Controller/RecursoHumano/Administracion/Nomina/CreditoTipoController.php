<?php


namespace App\Controller\RecursoHumano\Administracion\Nomina;

use App\Controller\MaestroController;
use App\Entity\RecursoHumano\RhuConcepto;
use App\Entity\RecursoHumano\RhuCreditoTipo;
use App\Form\Type\RecursoHumano\CreditoTipoType;
use App\General\General;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CreditoTipoController extends MaestroController
{

    public $tipo = "administracion";
    public $modelo = "RhuCreditoTipo";

    protected $clase = RhuCreditoTipo::class;
    protected $claseNombre = "RhuCreditoTipo";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Administracion";
    protected $grupo = "Nomina";
    protected $nombre = "CreditoTipo";


    /**
     * @Route("recursohumano/adminsitracion/nomina/creditoTipo/lista", name="recursohumano_administracion_nomina_creditoTipo_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoCreditoTipoPk', TextType::class, array('required' => false))
            ->add('nombre', TextType::class, array('required' => false))
            ->add('codigoConceptoFk',EntityType::class,[
                'class' => RhuConcepto::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('er')
                        ->orderBy('er.nombre');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'label' => 'Concepto:',
                'placeholder'=>'todos'
            ])
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
                General::get()->setExportar($em->getRepository(RhuCreditoTipo::class)->lista($raw), "Credito tipo");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(RhuCreditoTipo::class)->eliminar($arrSeleccionados);
            }
        }
        $arCreditoTipos = $paginator->paginate($em->getRepository(RhuCreditoTipo::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('recursohumano/administracion/nomina/creditoTipo/lista.html.twig', [
            'arCreditoTipos' => $arCreditoTipos,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("recursohumano/adminsitracion/nomina/creditoTipo/nuevo/{id}", name="recursohumano_administracion_nomina_creditoTipo_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCreditoTipo = new RhuCreditoTipo();
        if ($id != 0) {
            $arCreditoTipo = $em->getRepository(RhuCreditoTipo::class)->find($id);
        }
        $form = $this->createForm(CreditoTipoType::class, $arCreditoTipo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arCreditoTipo = $form->getData();
                $em->persist($arCreditoTipo);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_administracion_nomina_creditoTipo_detalle', array('id' => $arCreditoTipo->getCodigoCreditoTipoPk())));
            }
        }
        return $this->render('recursohumano/administracion/nomina/creditoTipo/nuevo.html.twig', [
            'arContratoTipo' => $arCreditoTipo,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("recursohumano/adminsitracion/nomnina/creditoTipo/detalle/{id}", name="recursohumano_administracion_nomina_creditoTipo_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCreditoTipo = $em->getRepository(RhuCreditoTipo::class)->find($id);
        return $this->render('recursohumano/administracion/nomina/creditoTipo/detalle.html.twig', [
            'arCreditoTipo' => $arCreditoTipo
        ]);

    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoCreditoTipo' => $form->get('codigoCreditoTipoPk')->getData(),
            'nombre' => $form->get('nombre')->getData(),
        ];
        $arConcepto = $form->get('codigoConceptoFk')->getData();

        if (is_object($arConcepto)) {
            $filtro['codigoConcepto'] = $arConcepto->getCodigoConceptoPk();
        } else {
            $filtro['codigoConcepto'] = $arConcepto;
        }
        return $filtro;
    }
}