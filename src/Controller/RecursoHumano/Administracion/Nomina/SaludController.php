<?php


namespace App\Controller\RecursoHumano\Administracion\Nomina;

use App\Entity\RecursoHumano\RhuSalud;
use App\Form\Type\RecursoHumano\SaludType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\General\General;

class SaludController extends AbstractController
{

    /**
     * @Route("recursohumano/adminsitracion/nomina/salud/lista", name="recursohumano_administracion_nomina_salud_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoSaludPk', TextType::class, array('required' => false))
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
                General::get()->setExportar($em->getRepository(RhuSalud ::class)->lista($raw), "Salud");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(RhuSalud::class)->eliminar($arrSeleccionados);
            }
        }
        $arSalud = $paginator->paginate($em->getRepository(RhuSalud::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('recursohumano/administracion/nomina/salud/lista.html.twig', [
            'arSalud' => $arSalud,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("recursohumano/adminsitracion/nomina/salud/nuevo/{id}", name="recursohumano_administracion_nomina_salud_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arSalud = new RhuSalud();
        if ($id != 0 || $id != "") {
            $arSalud = $em->getRepository(RhuSalud::class)->find($id);
        }
        $form = $this->createForm(SaludType::class, $arSalud);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arSalud = $form->getData();
                $em->persist($arSalud);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_administracion_nomina_salud_detalle', array('id' => $arSalud->getCodigoSaludPk())));
            }
        }
        return $this->render('recursohumano/administracion/nomina/salud/nuevo.html.twig', [
            'arSalud' => $arSalud,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("recursohumano/adminsitracion/nomnina/salud/detalle/{id}", name="recursohumano_administracion_nomina_salud_detalle")
     */
    public function detalle(Request $request, PaginatorInterface $paginator, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arSalud = $em->getRepository(RhuSalud::class)->find($id);
        return $this->render('recursohumano/administracion/nomina/salud/detalle.html.twig', [
            'arSalud' => $arSalud,
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoSalud' => $form->get('codigoSaludPk')->getData(),
            'nombre' => $form->get('nombre')->getData(),
        ];
        return $filtro;
    }
}