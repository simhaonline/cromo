<?php
namespace App\Controller\Transporte\Administracion\Comercial\Condicion;

use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Controller\Estructura\MensajesController;
use App\Controller\MaestroController;
use App\Entity\Transporte\TteClienteCondicion;
use App\Entity\Transporte\TteCondicion;
use App\Entity\Transporte\TteCondicionFlete;
use App\Entity\Transporte\TteDescuentoZona;
use App\Form\Type\Transporte\CondicionType;
use App\Form\Type\Transporte\CondicionFleteType;
use App\Utilidades\Estandares;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;

class CondicionController extends MaestroController
{

    public $tipo = "administracion";
    public $modelo = "TteCondicion";

    protected $class= TteCondicion::class;
    protected $claseNombre = "TteCondicion";
    protected $modulo = "Transporte";
    protected $funcion = "Administracion";
    protected $grupo = "Comercial";
    protected $nombre = "Condicion";

    /**
     * @Route("/transporte/administracion/comercial/condicion/lista", name="transporte_administracion_comercial_condicion_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('txtNombre', TextType::class, ['label' => 'Nombre: ', 'required' => false, 'data' => $session->get('filtroNombreCondicion')])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked()) {
            $session->set('filtroNombreCondicion', $form->get('txtNombre')->getData());
        }
        $arCondiciones = $paginator->paginate($em->getRepository(TteCondicion::class)->lista(), $request->query->getInt('page', 1),50);
        return $this->render('transporte/administracion/comercial/condicion/lista.html.twig',
            ['arCondiciones' => $arCondiciones,
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/transporte/administracion/comercial/condicion/detalle/{id}", name="transporte_administracion_comercial_condicion_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCondicion = $em->getRepository(TteCondicion::class)->find($id);
        $form = $this->createFormBuilder()

            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnEliminarDetalle')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(TteDescuentoZona::class)->eliminar($arrSeleccionados);
            }
        }
        return $this->render('transporte/administracion/comercial/condicion/detalle.html.twig', array(
            'arCondicion' => $arCondicion,
            'form' => $form->createView()
        ));
    }
    /**
     * @Route("/transporte/administracio/comercial/condicion/nuevo/{id}", name="transporte_administracion_comercial_condicion_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCondicion = new TteCondicion();
        if ($id != '0') {
            $arCondicion = $em->getRepository(TteCondicion::class)->find($id);
            if (!$arCondicion) {
                return $this->redirect($this->generateUrl('transporte_administracion_comercial_condicion_lista'));
            }
        }
        $form = $this->createForm(CondicionType::class, $arCondicion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arCondicion);
                $em->flush();
                return $this->redirect($this->generateUrl('transporte_administracion_comercial_condicion_detalle', ['id' => $arCondicion->getCodigoCondicionPk()]));
            }
        }
        return $this->render('transporte/administracion/comercial/condicion/nuevo.html.twig', [
            'arCondicion' => $arCondicion,
            'form' => $form->createView()
        ]);
    }

}

