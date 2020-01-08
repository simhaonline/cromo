<?php

namespace App\Controller\RecursoHumano\Administracion\Contratacion;

use App\Controller\Estructura\ControllerListenerGeneral;
use App\Entity\RecursoHumano\RhuRequisitoConcepto;
use App\Form\Type\RecursoHumano\RequisitoConceptoType;
use App\General\General;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RequisitoConceptoController extends AbstractController
{
    /**
     * @Route("/recursohumano/administracion/contratacion/requisitoconcepto/lista", name="recursohumano_administracion_contratacion_requisitoconcepto_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $this->request = $request;
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoRequisitoConceptoPk', TextType::class, array('required' => false))
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
                General::get()->setExportar($em->getRepository(RhuRequisitoConcepto::class)->lista($raw), "Requisitos conceptos");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(RhuRequisitoConcepto::class)->eliminar($arrSeleccionados);
            }
        }
        $arRequisitosConceptos = $paginator->paginate($em->getRepository(RhuRequisitoConcepto::class)->lista($raw), $request->query->getInt('page', 1), 50);
        return $this->render('recursohumano/administracion/contratacion/requisitoconcepto/lista.html.twig', [
            'arRequisitosConceptos' => $arRequisitosConceptos,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/recursohumano/administracion/contratacion/requisitoconcepto/nuevo/{id}", name="recursohumano_administracion_contratacion_requisitoconcepto_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRequisitoConceptos = new RhuRequisitoConcepto();
        if ($id != 0) {
            $arRequisitoConceptos = $em->getRepository(RhuRequisitoConcepto::class)->find($id);
            if (!$arRequisitoConceptos) {
                return $this->redirect($this->generateUrl('recursohumano_administracion_contratacion_requisitoconcepto_lista'));
            }
        }
        $form = $this->createForm(RequisitoConceptoType::class, $arRequisitoConceptos);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arRequisitoConceptos);
                $em->flush();
            }
            return $this->redirect($this->generateUrl('recursohumano_administracion_contratacion_requisitoconcepto_detalle', array('id' => $arRequisitoConceptos->getCodigoRequisitoConceptoPk())));
        }
        return $this->render('recursohumano/administracion/contratacion/requisitoconcepto/nuevo.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/recursohumano/administracion/contratacion/requisitoconcepto/detalle/{id}", name="recursohumano_administracion_contratacion_requisitoconcepto_detalle")
     */
    public function detalle(Request $request, PaginatorInterface $paginator,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRequisitosConcepto = $em->getRepository(RhuRequisitoConcepto::class)->find($id);
        return $this->render('recursohumano/administracion/contratacion/requisitoconcepto/detalle.html.twig', [
            'arRequisitosConcepto' => $arRequisitosConcepto,
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoRequisitoConcepto' => $form->get('codigoRequisitoConceptoPk')->getData(),
            'nombre' => $form->get('nombre')->getData(),
        ];

        return $filtro;

    }
}

