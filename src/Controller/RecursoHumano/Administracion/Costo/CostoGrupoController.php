<?php


namespace App\Controller\RecursoHumano\Administracion\Costo;


use App\Entity\RecursoHumano\RhuCostoGrupo;
use App\Form\Type\RecursoHumano\CostoGrupoType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\General\General;

class CostoGrupoController extends  AbstractController
{
    /**
     * @Route("recursohumano/adminsitracion/costo/grupo/lista", name="recursohumano_administracion_costo_grupo_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoCostoGrupoPk', TextType::class, array('required' => false))
            ->add('nombre', TextType::class, array('required' => false))
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
                General::get()->setExportar($em->getRepository(RhuCostoGrupo::class)->lista($raw), "costos grupo");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(RhuCostoGrupo::class)->eliminar($arrSeleccionados);
            }
        }
        $arCostoGrupos = $paginator->paginate($em->getRepository(RhuCostoGrupo::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('recursohumano/administracion/costo/grupo/lista.html.twig', [
            'arCostoGrupos' => $arCostoGrupos,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("recursohumano/adminsitracion/costo/grupo/nuevo/{id}", name="recursohumano_administracion_costo_grupo_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCostroGrupo = new RhuCostoGrupo();
        if ($id != 0 || $id != "") {
            $arCostroGrupo = $em->getRepository(RhuCostoGrupo::class)->find($id);
        }
        $form = $this->createForm(CostoGrupoType::class, $arCostroGrupo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arCostroGrupo = $form->getData();
                $em->persist($arCostroGrupo);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_administracion_costo_grupo_detalle', array('id' => $arCostroGrupo->getCodigoCostoGrupoPk())));
            }
        }
        return $this->render('recursohumano/administracion/costo/grupo/nuevo.html.twig', [
            'arCostroGrupo' => $arCostroGrupo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("recursohumano/adminsitracion/costo/grupo/detalle/{id}", name="recursohumano_administracion_costo_grupo_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCostroGrupo = $em->getRepository(RhuCostoGrupo::class)->find($id);
        return $this->render('recursohumano/administracion/costo/grupo/detalle.html.twig', [
            'arCostroGrupo' => $arCostroGrupo,
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoCostoGrupo' => $form->get('codigoCostoGrupoPk')->getData(),
            'nombre' => $form->get('nombre')->getData(),
        ];
        return $filtro;
    }
}