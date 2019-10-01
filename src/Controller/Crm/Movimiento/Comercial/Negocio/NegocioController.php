<?php


namespace App\Controller\Crm\Movimiento\Comercial\Negocio;


use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Crm\CrmCliente;
use App\Entity\Crm\CrmContacto;
use App\Entity\Crm\CrmNegocio;
use App\Form\Type\Crm\NegocioType;
use App\General\General;
use Knp\Component\Pager\PaginatorInterface;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class NegocioController extends AbstractController
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
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoClienteFk', TextType::class, ['required' => false])
            ->add('estadoGanado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoCerrado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
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
                $raw['filtros'] = $this->getFiltros($form);
                General::get()->setExportar($em->getRepository(CrmNegocio::class)->lista($raw)->getQuery()->execute(), "Negocios");
            }
            if ($form->get('btnEliminar')->isClicked()){
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $this->get("UtilidadesModelo")->eliminar(CrmNegocio::class, $arrSeleccionados);
                return $this->redirect($this->generateUrl('crm_movimiento_comercial_negocio_lista'));
            }
        }
        $arNegocios = $paginator->paginate($em->getRepository(CrmNegocio::class)->lista($raw), $request->query->getInt('page', 1), 500);
        return $this->render('crm/movimiento/comercial/negocio/lista.html.twig', [
            'arNegocios' => $arNegocios,
            'form' => $form->createView()
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
        }else{
            $arNegocio->setFechaCierre(new \DateTime('now'));
            $arNegocio->setFechaNegocio(new \DateTime('now'));
        }
        $form = $this->createForm(NegocioType::class, $arNegocio);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arNegocio->setFecha(new \DateTime('now'));
                $arCliente =$em->getRepository(CrmCliente::class)->find($form->get('codigoClienteFk')->getData());
                $arContacto =$em->getRepository(CrmContacto::class)->find($form->get('codigoContactoFk')->getData());
                if ($arCliente){
                    $arNegocio = $form->getData();
                    $arNegocio->setClienteRel($arCliente);
                    $arNegocio->setContactoRel($arContacto);
                    $em->persist($arNegocio);
                    $em->flush();
                    return $this->redirect($this->generateUrl('crm_movimiento_comercial_negocio_detalle', ['id' => $arNegocio->getCodigoNegocioPk()]));
                }

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

    public function getFiltros($form)
    {
        $filtro = [
            'codigoCliente' => $form->get('CodigoClienteFk')->getData(),
            'estadoGanado' => $form->get('estadoGanado')->getData(),
            'estadoCerrado' => $form->get('estadoCerrado')->getData(),
        ];

        return $filtro;

    }
}