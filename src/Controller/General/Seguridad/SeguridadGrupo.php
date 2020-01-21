<?php


namespace App\Controller\General\Seguridad;


use App\Entity\Seguridad\SegGrupo;
use App\Form\Type\General\GrupoType;
use App\General\General;
use App\Utilidades\Estandares;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class SeguridadGrupo extends AbstractController
{
    /**
     * @Route("/general/seguridad/grupo/lista", name="general_seguridad_grupo_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $session = new Session();
        $raw = [
            'filtros'=> $session->get('filtroSegGrupo')
        ];
        $form = $this->createFormBuilder()
            ->add('codigoGrupoPk', TextType::class, array('required' => false, 'data'=>$raw['filtros']['codigoGrupo'] ))
            ->add('nombre', TextType::class, array('required' => false, 'data'=>$raw['filtros']['nombre']))
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnEliminar', SubmitType::class, array('label' => 'Eliminar'))
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->setMethod('GET')
            ->getForm();
        $form->handleRequest($request);
        $raw['limiteRegistros'] = $form->get('limiteRegistros')->getData();
        if ($form->isSubmitted()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }
            if ($form->get('btnExcel')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $raw['filtros'] = $this->getFiltros($form);
                General::get()->setExportar($em->getRepository(SegGrupo::class)->lista($raw), "seguridad grupo");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(SegGrupo::class)->eliminar($arrSeleccionados);
            }
        }
        $arGrupos = $paginator->paginate($em->getRepository(SegGrupo::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('general/seguridad/grupo/lista.html.twig', [
            'arGrupos' => $arGrupos,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/general/seguridad/grupo/nuevo/{id}", name="general_seguridad_grupo_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arGrupo = new SegGrupo();
        if ($id != 0) {
            $arGrupo = $em->getRepository(SegGrupo::class)->find($id);
            if (!$arGrupo) {
                return $this->redirect($this->generateUrl('general_seguridad_grupo_lista'));
            }
        }
        $form = $this->createForm( GrupoType::class, $arGrupo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arEmbargoJusgado = $form->getData();
                $em->persist($arEmbargoJusgado);
                $em->flush();
                return $this->redirect($this->generateUrl('general_seguridad_grupo_detalle', array('id' => $arGrupo->getCodigoGrupoPK())));
            }
        }
        return $this->render('general/seguridad/grupo/nuevo.html.twig', [
            'arGrupo' => $arGrupo,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/general/seguridad/grupo/detalle/{id}", name="general_seguridad_grupo_detalle")
     */
    public function detalle(Request $request, PaginatorInterface $paginator,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $arGrupo = $em->getRepository(SegGrupo::class)->find($id);

        return $this->render('general/seguridad/grupo/detalle.html.twig', [
            'arGrupo' => $arGrupo,
        ]);
    }

    public function getFiltros($form)
    {
        $session = new Session();

        $filtro = [
            'codigoGrupo' => $form->get('codigoGrupoPk')->getData(),
            'nombre' => $form->get('nombre')->getData(),
        ];
        $session->set('filtroSegGrupo', $filtro);

        return $filtro;

    }
}