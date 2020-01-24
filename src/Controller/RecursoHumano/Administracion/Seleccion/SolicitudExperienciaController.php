<?php


namespace App\Controller\RecursoHumano\Administracion\Seleccion;

use App\Controller\MaestroController;
use App\Entity\RecursoHumano\RhuSolicitudExperiencia;
use App\Form\Type\RecursoHumano\SolicitudExperienciaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\General\General;

class SolicitudExperienciaController extends MaestroController
{

    public $tipo = "Administracion";
    public $modelo = "RhuSolicitudExperiencia";

    protected $clase = RhuSolicitudExperiencia::class;

    protected $claseNombre = "RhuSolicitudExperiencia";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Administracion";
    protected $grupo = "Seleccion";
    protected $nombre = "SolicitudExperiencia";



    /**
     * @Route("recursohumano/administracion/seleccion/solicitudadexperiencia/lista", name="recursohumano_administracion_seleccion_solicitudadexperiencia_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoSolicitudExperienciaPk', TextType::class, ['required' => false])
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
                General::get ()->setExportar ($em->getRepository (RhuSolicitudExperiencia::class)->lista ($raw), "solicitud experecia");
            }
            if ($form->get ('btnEliminar')->isClicked ()) {
                $arrSeleccionados = $request->query->get ('ChkSeleccionar');
                $em->getRepository (RhuSolicitudExperiencia::class)->eliminar ($arrSeleccionados);
            }
        }
        $arSolicitudExperiencias = $paginator->paginate ($em->getRepository (RhuSolicitudExperiencia::class)->lista ($raw), $request->query->getInt ('page', 1), 30);
        return $this->render ('recursohumano/administracion/seleccion/solicitudexperiencia/lista.html.twig', [
            'arSolicitudExperiencias' => $arSolicitudExperiencias,
            'form' => $form->createView ()
        ]);
    }

    /**
     * @Route("recursohumano/administracion/seleccion/solicitudadexperiencia/nuevo/{id}", name="recursohumano_administracion_seleccion_solicitudadexperiencia_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arSolicitudExperiencia = new RhuSolicitudExperiencia();
        if ($id != 0) {
            $arSolicitudExperiencia = $em->getRepository(RhuSolicitudExperiencia::class)->find($id);
            if (!$arSolicitudExperiencia) {
                return $this->redirect($this->generateUrl('recursohumano_administracion_nomina_embargojuzgado_lista'));
            }
        }
        $form = $this->createForm(SolicitudExperienciaType::class, $arSolicitudExperiencia);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arSolicitudExperiencia = $form->getData();
                $em->persist($arSolicitudExperiencia);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_administracion_seleccion_solicitudadexperiencia_detalle', array('id' => $arSolicitudExperiencia->getCodigoSolicitudExperienciaPK())));
            }
        }
        return $this->render ('recursohumano/administracion/seleccion/solicitudexperiencia/nuevo.html.twig', [
            'arSolicitudExperiencia' => $arSolicitudExperiencia,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("recursohumano/administracion/seleccion/solicitudadexperiencia/detalle/{id}", name="recursohumano_administracion_seleccion_solicitudadexperiencia_detalle")
     */
    public function detalle(Request $request, PaginatorInterface $paginator,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $arSolicitudExperiencia = $em->getRepository(RhuSolicitudExperiencia::class)->find($id);

        return $this->render ('recursohumano/administracion/seleccion/solicitudexperiencia/detalle.html.twig', [
            'arSolicitudExperiencia' => $arSolicitudExperiencia,
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoSolicitudExperiencia' => $form->get('codigoSolicitudExperienciaPk')->getData(),
            'nombre' => $form->get('nombre')->getData(),
        ];
        return $filtro;
    }
}