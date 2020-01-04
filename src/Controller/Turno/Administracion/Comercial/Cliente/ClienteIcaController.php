<?php


namespace App\Controller\Turno\Administracion\Comercial\Cliente;


use App\Entity\Turno\TurClienteIca;
use App\Form\Type\Turno\ClienteIcaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\General\General;


class ClienteIcaController extends AbstractController
{
    /**
     * @Route("/turno/administracion/comercial/cliente/ica/lista", name="turno_administracion_comercial__cliente_ica_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnEliminar', SubmitType::class, array('label' => 'Eliminar'))
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->add('codigoClienteIcaPk', TextType::class, array('required' => false))
            ->add('numeroIdentificacion', TextType::class, array('required' => false))
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
                General::get()->setExportar($em->getRepository(TurClienteIca::class)->lista($raw), "Clientes ica");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(TurClienteIca::class)->eliminar($arrSeleccionados);
            }
        }
        $arClientesIca = $paginator->paginate($em->getRepository(TurClienteIca::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('transporte/movimiento/transporte/guia/lista.html.twig', [
            'arClientesIca' => $arClientesIca,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/turno/administracion/comercial/cliente/ica/nuevo/{id}", name="turno_administracion_comercial__cliente_ica_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arClienteIca = new TurClienteIca();
        if ($id != 0) {
            $arClienteIca = $em->getRepository(TurClienteIca::class)->find($id);
            if (!$arClienteIca) {
                return $this->redirect($this->generateUrl('turno_administracion_comercial__cliente_ica_lista'));
            }
        }
        $form = $this->createForm(ClienteIcaType::class, $arClienteIca);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arClienteIca = $form->getData();
                $em->persist($arClienteIca);
                $em->flush();
                return $this->redirect($this->generateUrl('turno_administracion_comercial__cliente_ica_detalle', array('id' => $arClienteIca->getCodigoClienteIcaPk())));
            }
        }
        return $this->render('recursohumano/administracion/nomina/juzgado/nuevo.html.twig', [
            'arEmbargoJusgado' => $arClienteIca,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/turno/administracion/comercial/cliente/ica/detalle/{id}", name="turno_administracion_comercial__cliente_ica_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arClienteIca = $em->getRepository(TurClienteIca::class)->find($id);
        return $this->render('recursohumano/movimiento/recurso/incidente/detalle.html.twig', [
            'arClienteIca' => $arClienteIca,
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoClienteIca' => $form->get('codigoClienteIcaPk')->getData(),
            'numeroIdentificacion' => $form->get('numeroIdentificacion')->getData(),
        ];

        return $filtro;

    }
}