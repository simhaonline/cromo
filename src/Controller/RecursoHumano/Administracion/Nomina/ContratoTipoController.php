<?php


namespace App\Controller\RecursoHumano\Administracion\Nomina;


use App\Entity\RecursoHumano\RhuContratoClase;
use App\Entity\RecursoHumano\RhuContratoTipo;
use App\Form\Type\RecursoHumano\ContratoTipoType;
use App\General\General;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContratoTipoController extends AbstractController
{
    /**
     * @Route("recursohumano/adminsitracion/nomina/contratoTipo/lista", name="recursohumano_administracion_nomina_contratoTipo_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoContratoTipoPk', TextType::class, array('required' => false))
            ->add('nombre', TextType::class, array('required' => false))
            ->add('codigoContratoClaseFk', EntityType::class, [
                'class' => RhuContratoClase::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.codigoContratoClasePk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS'
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
                General::get()->setExportar($em->getRepository(RhuContratoTipo::class)->lista($raw), "contrato tipo");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(RhuContratoTipo::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl(''));
            }
        }
        $arContratoTipos = $paginator->paginate($em->getRepository(RhuContratoTipo::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('recursohumano/administracion/nomina/contratoTipo/lista.html.twig', [
            'arContratoTipos' => $arContratoTipos,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("recursohumano/adminsitracion/nomina/contratoTipo/nuevo/{id}", name="recursohumano_administracion_nomina_contratoTipo_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arContratoTipo = new RhuContratoTipo();
        if ($id != 0) {
            $arContratoTipo = $em->getRepository(RhuContratoTipo::class)->find($id);
            if (!$arContratoTipo) {
                return $this->redirect($this->generateUrl('recursohumano_administracion_nomina_embargojuzgado_lista'));
            }
        }
        $form = $this->createForm(ContratoTipoType::class, $arContratoTipo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arContratoTipo = $form->getData();
                $em->persist($arContratoTipo);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_administracion_nomina_contratoTipo_detalle', array('id' => $arContratoTipo->getCodigoContratoTipoPk())));
            }
        }
        return $this->render('recursohumano/administracion/nomina/contratoTipo/nuevo.html.twig', [
            'arContratoTipo' => $arContratoTipo,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("recursohumano/adminsitracion/nomnina/contratoTipo/detalle/{id}", name="recursohumano_administracion_nomina_contratoTipo_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arContratoTipo = $em->getRepository(RhuContratoTipo::class)->find($id);
        return $this->render('recursohumano/administracion/nomina/contratoTipo/detalle.html.twig', [
            'arContratoTipo' => $arContratoTipo
        ]);

    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoContratoTipo' => $form->get('codigoContratoTipoPk')->getData(),
            'nombre' => $form->get('nombre')->getData(),
        ];
        $arContratoClase = $form->get('codigoContratoClaseFk')->getData();

        if (is_object($arContratoClase)) {
            $filtro['codigoContratoClase'] = $arContratoClase->getCodigoContratoClasePk();
        } else {
            $filtro['codigoContratoClase'] = $arContratoClase;
        }
        return $filtro;
    }
}