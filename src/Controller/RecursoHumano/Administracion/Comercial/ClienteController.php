<?php

namespace App\Controller\RecursoHumano\Administracion\Comercial;


use App\Entity\RecursoHumano\RhuCliente;
use App\Form\Type\RecursoHumano\ClienteType;
use App\General\General;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ClienteController extends AbstractController
{
    protected $clase = RhuCliente::class;
    protected $claseNombre = "RhuCliente";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Administracion";
    protected $grupo = "Comercial";
    protected $nombre = "Cliente";

    /**
     * @param Request $request
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/recursohumano/administracion/comercial/cliente/lista", name="recursohumano_administracion_comercial_cliente_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $this->request = $request;
        $em = $this->getDoctrine()->getManager();
        $session = new Session();
        $form = $this->createFormBuilder()
            ->add('txtCodigoCliente', TextType::class, array('required' => false, 'data' => $session->get('filtroTurClienteCodigo')))
            ->add('txtNumeroIdentificacion', TextType::class, array('required' => false, 'data' => $session->get('filtroTurClienteIdentificacion')))
            ->add('txtNombre', TextType::class, array('required' => false, 'data' => $session->get('filtroTurClienteNombre')))
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn-sm btn btn-danger']])
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn-sm btn btn-default']])
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtro'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroTurClienteCodigo', $form->get('txtCodigoCliente')->getData());
                $session->set('filtroTurClienteIdentificacion', $form->get('txtNumeroIdentificacion')->getData());
                $session->set('filtroTurClienteNombre', $form->get('txtNombre')->getData());
            }
        }
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(RhuCliente::class)->lista(), "Clientes");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arData = $request->request->get('ChkSeleccionar');
                $this->get("UtilidadesModelo")->eliminar(RhuCliente::class, $arData);
            }
        }
        $arClientes = $paginator->paginate($em->getRepository(RhuCliente::class)->lista(), $request->query->getInt('page', 1), 50);
        return $this->render('recursohumano/administracion/comercial/cliente/lista.html.twig', [
            'arClientes' => $arClientes,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/recursohumano/administracion/comercial/cliente/nuevo/{id}", name="recursohumano_administracion_comercial_cliente_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCliente = new RhuCliente();
        if ($id != '0') {
            $arCliente = $em->getRepository(RhuCliente::class)->find($id);
            if (!$arCliente) {
                return $this->redirect($this->generateUrl('recursohumano_administracion_comercial_cliente_lista'));
            }
        }
        $form = $this->createForm(ClienteType::class, $arCliente);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arCliente);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_administracion_comercial_cliente_detalle', ['id' => $arCliente->getCodigoClientePk()]));
            }
        }
        return $this->render('recursohumano/administracion/comercial/cliente/nuevo.html.twig', [
            'arCliente' => $arCliente,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/recursohumano/administracion/comercial/cliente/detalle/{id}", name="recursohumano_administracion_comercial_cliente_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCliente = $em->getRepository(RhuCliente::class)->find($id);
        $form = $this->createFormBuilder()
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

        }
        return $this->render('recursohumano/administracion/comercial/cliente/detalle.html.twig', array(
            'arCliente' => $arCliente,
            'form' => $form->createView()

        ));
    }
}

