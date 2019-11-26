<?php


namespace App\Controller\Turno\Administracion\Comercial\Puesto;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Transporte\TteCliente;
use App\Entity\Turno\TurCliente;
use App\Entity\Turno\TurPuesto;
use App\Entity\Turno\TurPuestoAdicional;
use App\Form\Type\Turno\PuestoAdicionalType;
use App\Form\Type\Turno\PuestoType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PuestoController extends AbstractController
{

    /**
     * @param Request $request
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/turno/administracion/comercial/puesto/lista", name="turno_administracion_comercial_puesto_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']))
            ->getForm();
        $form->handleRequest($request);
        $arPuestos = $paginator->paginate($em->getRepository(TurPuesto::class)->lista(), $request->query->getInt('page', 1), 30);
        return $this->render('turno/administracion/comercial/puesto/lista.html.twig', [
            'arPuestos' => $arPuestos,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/turno/administracion/puesto/detalle/{id}", name="turno_administracion_comercial_puesto_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arPuesto = $em->getRepository(TurPuesto::class)->find($id);
        $arPuestoAdicionales = $em->getRepository(TurPuestoAdicional::class)->lista($id);
        return $this->render('turno/administracion/comercial/puesto/detalle.html.twig', array(
            'arPuesto' => $arPuesto,
            'arPuestoAdicionales' => $arPuestoAdicionales
        ));
    }

    /**
     * @Route("/turno/administracion/comercial/puesto/adicional/nuevo/{codigoPuesto}/{id}", name="turno_administracion_comercial_puesto_adicional_nuevo")
     */
    public function puestoAdicionalNuevo(Request $request, $codigoPuesto, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arPuesto = $em->getRepository(TurPuesto::class)->find($codigoPuesto);
        $arPuestoAdicional = new TurPuestoAdicional();
        if ($id != '0') {
            $arPuestoAdicional = $em->getRepository(TurPuestoAdicional::class)->find($id);
        }
        $form = $this->createForm(PuestoAdicionalType::class, $arPuestoAdicional);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arPuesto = $em->getRepository(TurPuesto::class)->find($codigoPuesto);
                $arPuestoAdicional->setPuestoRel($arPuesto);
                $em->persist($arPuestoAdicional);
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('turno/administracion/comercial/puesto/adicionalNuevo.html.twig', [
            'codigoPuesto' => $arPuesto,
            'id' => $arPuestoAdicional,
            'form' => $form->createView()
        ]);
    }

}