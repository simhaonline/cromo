<?php


namespace App\Controller\RecursoHumano\Administracion\Seleccion;

use App\Entity\RecursoHumano\RhuSeleccionTipo;
use App\Form\Type\RecursoHumano\SeleccionTipoType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\General\General;

class SeleccionTipoController extends AbstractController
{

    public $tipo = "administracion";
    public $modelo = "RhuSeleccionTipo";

    protected $clase = RhuSeleccionTipo::class;
    protected $claseNombre = "RhuSeleccionTipo";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Administracion";
    protected $grupo = "Seleccion";
    protected $nombre = "SeleccionTipoController";



    /**
     * @Route("recursohumano/administracion/seleccion/selecciontipo/lista", name="recursohumano_administracion_seleccion_selecciontipo_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoSolicitudTipoPk', TextType::class, ['required' => false])
            ->add('nombre', TextType::class, ['required' => false])
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnEliminar', SubmitType::class, array('label' => 'Eliminar'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->setMethod('GET')
            ->getForm();
        $form->handleRequest($request);
        $raw = [
            'limiteRegistros' => $form->get('limiteRegistros')->getData()
        ];
        if ($form->isSubmitted()) {
            if ($form->get ('btnFiltrar')->isClicked ()) {
                $raw['filtros'] = $this->getFiltros ($form);
            }
            if ($form->get ('btnExcel')->isClicked ()) {
                $raw['filtros'] = $this->getFiltros ($form);
                General::get ()->setExportar ($em->getRepository (RhuSeleccionTipo::class)->lista($raw), "seleccion tipo");
            }
            if ($form->get ('btnEliminar')->isClicked ()) {
                $arrSeleccionados = $request->query->get ('ChkSeleccionar');
                $em->getRepository (RhuSeleccionTipo::class)->eliminar ($arrSeleccionados);
            }
        }
        $arSeleccionTipos = $paginator->paginate ($em->getRepository (RhuSeleccionTipo::class)->lista($raw), $request->query->getInt ('page', 1), 30);
        return $this->render ('recursohumano/administracion/seleccion/selecciontipo/lista.html.twig', [
            'arSeleccionTipos' => $arSeleccionTipos,
            'form' => $form->createView ()
        ]);
    }

    /**
     * @Route("recursohumano/administracion/seleccion/selecciontipo/nuevo/{id}", name="recursohumano_administracion_seleccion_selecciontipo_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arSeleccionTipo = new RhuSeleccionTipo();
        if ($id != 0) {
            $arSeleccionTipo = $em->getRepository(RhuSeleccionTipo::class)->find($id);
            if (!$arSeleccionTipo) {
                return $this->redirect($this->generateUrl('recursohumano_administracion_nomina_embargojuzgado_lista'));
            }
        }
        $form = $this->createForm(SeleccionTipoType::class, $arSeleccionTipo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arSeleccionTipo = $form->getData();
                $em->persist($arSeleccionTipo);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_administracion_seleccion_selecciontipo_detalle', array('id' => $arSeleccionTipo->getCodigoSeleccionTipoPK())));
            }
        }
        return $this->render ('recursohumano/administracion/seleccion/selecciontipo/nuevo.html.twig', [
            'arSeleccionTipo' => $arSeleccionTipo,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("recursohumano/administracion/seleccion/selecciontipo/detalle/{id}", name="recursohumano_administracion_seleccion_selecciontipo_detalle")
     */
    public function detalle(Request $request, PaginatorInterface $paginator,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $arSeleccionTipo = $em->getRepository(RhuSeleccionTipo::class)->find($id);

        return $this->render ('recursohumano/administracion/seleccion/selecciontipo/detalle.html.twig', [
            'arSeleccionTipo' => $arSeleccionTipo,
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoSeleccionTipo' => $form->get('codigoSolicitudTipoPk')->getData(),
            'nombre' => $form->get('nombre')->getData(),
        ];
        return $filtro;
    }
}