<?php


namespace App\Controller\RecursoHumano\Administracion\Seleccion;

use App\Controller\MaestroController;
use App\Entity\RecursoHumano\RhuSolicitudMotivo;
use App\Form\Type\RecursoHumano\SolicitudMotivoType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\General\General;

class SolicitudMotivoController extends MaestroController
{

    public $tipo = "Administracion";
    public $modelo = "RhuSolicitudMotivo";

    protected $clase = RhuSolicitudMotivo::class;

    protected $claseNombre = "RhuSolicitudMotivo";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Administracion";
    protected $grupo = "Seleccion";
    protected $nombre = "SolicitudMotivo";



    /**
     * @Route("recursohumano/administracion/seleccion/solicitudmotivo/lista", name="recursohumano_administracion_seleccion_solicitudmotivo_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoSolicitudMotivoPk', TextType::class, ['required' => false])
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
                General::get ()->setExportar ($em->getRepository (RhuSolicitudMotivo::class)->lista ($raw), "SOLICITUD MOTIVO");
            }
            if ($form->get ('btnEliminar')->isClicked ()) {
                $arrSeleccionados = $request->query->get ('ChkSeleccionar');
                $em->getRepository (RhuSolicitudMotivo::class)->eliminar ($arrSeleccionados);
            }
        }
        $arSolicitudMotivos = $paginator->paginate ($em->getRepository (RhuSolicitudMotivo::class)->lista ($raw), $request->query->getInt ('page', 1), 30);
        return $this->render ('recursohumano/administracion/seleccion/solicitudmotivo/lista.html.twig', [
            'arSolicitudMotivos' => $arSolicitudMotivos,
            'form' => $form->createView ()
        ]);
    }

    /**
     * @Route("recursohumano/administracion/seleccion/solicitudmotivo/nuevo/{id}", name="recursohumano_administracion_seleccion_solicitudmotivo_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arSeleccionMotivo = new RhuSolicitudMotivo();
        if ($id != 0) {
            $arSeleccionMotivo = $em->getRepository(RhuSolicitudMotivo::class)->find($id);
            if (!$arSeleccionMotivo) {
                return $this->redirect($this->generateUrl('recursohumano_administracion_seleccion_solicitudmotivo_lista'));
            }
        }
        $form = $this->createForm(SolicitudMotivoType::class, $arSeleccionMotivo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arSeleccionMotivo = $form->getData();
                $em->persist($arSeleccionMotivo);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_administracion_seleccion_solicitudmotivo_detalle', array('id' => $arSeleccionMotivo->getCodigoSolicitudMotivoPk())));
            }
        }
        return $this->render ('recursohumano/administracion/seleccion/solicitudmotivo/nuevo.html.twig', [
            'arSeleccionMotivo' => $arSeleccionMotivo,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("recursohumano/administracion/seleccion/solicitudmotivo/detalle/{id}", name="recursohumano_administracion_seleccion_solicitudmotivo_detalle")
     */
    public function detalle(Request $request, PaginatorInterface $paginator,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $arSolicitudMotivo = $em->getRepository(RhuSolicitudMotivo::class)->find($id);
        return $this->render ('recursohumano/administracion/seleccion/solicitudmotivo/detalle.html.twig', [
            'arSolicitudMotivo' => $arSolicitudMotivo,
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoSolicitudMotivo' => $form->get('codigoSolicitudMotivoPk')->getData(),
            'nombre' => $form->get('nombre')->getData(),
        ];
        return $filtro;
    }
}