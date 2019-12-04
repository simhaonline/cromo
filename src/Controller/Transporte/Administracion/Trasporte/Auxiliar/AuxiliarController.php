<?php


namespace App\Controller\Transporte\Administracion\Trasporte\Auxiliar;


use App\Entity\Transporte\TteAuxiliar;
use App\Form\Type\Transporte\AuxiliarType;
use App\General\General;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AuxiliarController extends AbstractController
{
    /**
     * @Route("/transporte/administracion/transporte/auxiliar/lista", name="transporte_administracion_transporte_auxiliar_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoAxuiliar', TextType::class, array('required' => false))
            ->add('numeroIdentificacion', TextType::class, array('required' => false))
            ->add('nombre', TextType::class, array('required' => false))
            ->add('estadoInactivo', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
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
                General::get()->setExportar($em->getRepository(TteAuxiliar::class)->lista($raw), "Auxiliares");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(TteAuxiliar::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('transporte_administracion_transporte_auxiliar_lista'));
            }
        }
        $arAuxiliares = $paginator->paginate($em->getRepository(TteAuxiliar::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('transporte/administracion/transporte/auxiliar/lista.html.twig', [
            'arAuxiliares' => $arAuxiliares,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/transporte/administracion/comercial/auxiliar/nuevo/{id}", name="transporte_administracion_comercial_auxiliar_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arAuxiliar = new TteAuxiliar();
        if ($id != 0) {
            $arAuxiliar = $em->getRepository(TteAuxiliar::class)->find($id);
            if (!$arAuxiliar) {
                return $this->redirect($this->generateUrl('transporte_administracion_transporte_auxiliar_lista'));
            }
        }
        $form = $this->createForm(AuxiliarType::class, $arAuxiliar);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arAuxiliar = $form->getData();
                $em->persist($arAuxiliar);
                $em->flush();
                return $this->redirect($this->generateUrl('transporte_administracion_comercial_auxiliar_detalle', array('id' => $arAuxiliar->getCodigoAuxiliarPK())));
            }
        }
        return $this->render('transporte/administracion/transporte/auxiliar/nuevo.html.twig', [
            'arAuxiliar' => $arAuxiliar,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/transporte/administracion/comercial/auxiliar/detalle/{id}", name="transporte_administracion_comercial_auxiliar_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arAuxiliar = $em->getRepository(TteAuxiliar::class)->find($id);
        return $this->render('transporte/administracion/transporte/auxiliar/detalle.html.twig', [
            'arAuxiliar' => $arAuxiliar,
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoAxuiliar' => $form->get('codigoAxuiliar')->getData(),
            'numeroIdentificacion' => $form->get('numeroIdentificacion')->getData(),
            'nombre' => $form->get('nombre')->getData(),
            'estadoInactivo' => $form->get('estadoInactivo')->getData(),
        ];

        return $filtro;

    }
}