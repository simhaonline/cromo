<?php

namespace App\Controller\Inventario\Administracion;

use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\MaestroController;
use App\Entity\Inventario\InvContacto;
use App\Entity\Inventario\InvSucursal;
use App\Entity\Inventario\InvTercero;
use App\Form\Type\Inventario\ContactoType;
use App\Form\Type\Inventario\SucursalType;
use App\Form\Type\Inventario\TerceroType;
use App\General\General;
use App\Utilidades\Mensajes;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class ContactoController extends MaestroController
{

    public $tipo = "Administracion";
    public $modelo = "InvContacto";

    protected $class = InvTercero::class;
    protected $claseNombre = "InvContacto";
    protected $modulo = "Inventario";
    protected $funcion = "Administracion";
    protected $grupo = "General";
    protected $nombre = "Contacto";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/inventario/administracion/general/contacto/lista",name="inventario_administracion_general_contacto_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoTercero', TextType::class, ['required' => false, 'attr' => ['class' => 'form-control']])
            ->add('tercero', TextType::class, ['required' => false, 'attr' => ['class' => 'form-control']])
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
                General::get()->setExportar($em->getRepository(InvContacto::class)->lista($raw), "Contactos");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(InvContacto::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('inventario_administracion_general_contacto_lista'));
            }
        }
        $arContactos = $paginator->paginate($em->getRepository(InvContacto::class)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('inventario/administracion/general/contacto/lista.html.twig', [
            'arContactos' => $arContactos,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/inventario/administracion/general/contacto/detalle/{id}",name="inventario_administracion_general_contacto_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arContacto = $em->getRepository(InvContacto::class)->find($id);
        return $this->render('inventario/administracion/general/contacto/detalle.html.twig', [
            'arContacto' => $arContacto
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/inventario/administracion/general/contacto/nuevo/{id}",name="inventario_administracion_general_contacto_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arContacto = new InvContacto();
        if ($id != 0) {
            $arContacto = $em->getRepository(InvContacto::class)->find($id);
        }
        $form = $this->createForm(ContactoType::class, $arContacto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $txtCodigoTercero = $request->request->get('txtCodigoTercero');
                if ($txtCodigoTercero != '') {
                    $arTercero = $em->getRepository(InvTercero::class)->find($txtCodigoTercero);
                    if ($arTercero) {
                        $arContacto->setTerceroRel($arTercero);
                        $em->persist($arContacto);
                        $em->flush();
                        return $this->redirect($this->generateUrl('inventario_administracion_general_contacto_detalle', ['id' => $arContacto->getCodigoContactoPk()]));
                    }
                }
            } else {
                Mensajes::error('Debes seleccionar un tercero');
            }
        }
        return $this->render('inventario/administracion/general/contacto/nuevo.html.twig', [
            'arContacto' => $arContacto,
            'form' => $form->createView()
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoTercero' => $form->get('codigoTercero')->getData(),
            'tercero' => $form->get('tercero')->getData()
        ];

        return $filtro;

    }
}