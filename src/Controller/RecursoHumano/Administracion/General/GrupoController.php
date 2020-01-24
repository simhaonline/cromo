<?php


namespace App\Controller\RecursoHumano\Administracion\General;


use App\Controller\MaestroController;
use App\Entity\RecursoHumano\RhuGrupo;
use App\Form\Type\RecursoHumano\GrupoType;
use App\General\General;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class GrupoController extends  MaestroController
{


    public $tipo = "Movimiento";
    public $modelo = "RhuGrupo";


    protected $clase = RhuGrupoController::class;
    protected $claseNombre = "RhuGrupo";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Administracion";
    protected $grupo = "General";
    protected $nombre = "Grupo";


    /**
	 * @param Request $request
	 * @Route("recursohumano/adminsitracion/general/grupo/lista", name="recursohumano_administracion_general_grupo_lista")
	 */
	public function lista(Request $request, PaginatorInterface $paginator)
	{
		$em = $this->getDoctrine()->getManager();
		$form = $this->createFormBuilder()
			->add('codigoGrupoPk', TextType::class, ['required' => false])
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
				General::get ()->setExportar ($em->getRepository (RhuGrupo::class)->lista ($raw), "Grupos");
			}
			if ($form->get ('btnEliminar')->isClicked ()) {
				$arrSeleccionados = $request->query->get ('ChkSeleccionar');
				$em->getRepository (RhuGrupo::class)->eliminar ($arrSeleccionados);
				return $this->redirect ($this->generateUrl ('recursohumano_administracion_general_grupo_lista'));
			}
		}
		$arGrupos = $paginator->paginate ($em->getRepository (RhuGrupo::class)->lista ($raw), $request->query->getInt ('page', 1), 30);
		return $this->render ('recursohumano/administracion/general/grupo/lista.html.twig', [
			'arGrupos' => $arGrupos,
			'form' => $form->createView ()
		]);
		}


	/**
	 * @Route("recursohumano/adminsitracion/general/grupo/nuevo/{id}", name="recursohumano_administracion_general_grupo_nuevo")
	 */
	public function nuevo(Request $request, $id){
		$em = $this->getDoctrine()->getManager();
		$arGrupo = $em->getRepository(RhuGrupo::class)->find($id);
		if ($id == 0 ){
			$arGrupo = new RhuGrupo();
		}
		$form = $this->createForm(GrupoType::class, $arGrupo);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			if ($form->get('guardar')->isClicked()) {
				$em->persist($form->getData());
				$em->flush();
				return $this->redirect($this->generateUrl('recursohumano_administracion_general_grupo_detalle', array('id' => $arGrupo->getCodigoGrupoPk())));
			}
		}
		return $this->render('recursohumano/administracion/general/grupo/nuevo.html.twig', [
			'form'=>$form->createView(),
			'arGrupo'=>$arGrupo
		]);
	}

	/**
	 * @Route("recursohumano/adminsitracion/general/grupo/detalle/{id}", name="recursohumano_administracion_general_grupo_detalle")
	 */
	public function detalle(Request $request, $id){
		$em = $this->getDoctrine()->getManager();
		$arGrupo = $em->getRepository(RhuGrupo::class)->find($id);
		return $this->render('recursohumano/administracion/general/grupo/detalle.html.twig', [
			'arGrupo' => $arGrupo]);
	}

	public function getFiltros($form)
	{
		$filtro = [
			'codigoGrupo' => $form->get('codigoGrupoPk')->getData(),
			'nombre' => $form->get('nombre')->getData(),
		];

		return $filtro;

	}
}