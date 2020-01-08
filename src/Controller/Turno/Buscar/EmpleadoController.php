<?php


namespace App\Controller\Turno\Buscar;


use App\Entity\RecursoHumano\RhuEmpleado;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EmpleadoController extends  AbstractController
{

    /**
     * @Route("/turno/buscar/cliente/{campoCodigo}", name="turno_buscar_empleado")
     */
    public function lista(Request $request,  PaginatorInterface $paginator, $campoCodigo)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('txtNombre', TextType::class, ['required' => false])
            ->add('txtCodigo', TextType::class, ['required' => false])
            ->add('txtIdentificacion', TextType::class, ['required' => false])
            ->add('chkEstadoContrato', CheckboxType::class, ['label' => ' ', 'required' => false])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar'])
            ->setMethod('GET')
            ->getForm();
        $form->handleRequest($request);
        $raw = [];
        if ($form->isSubmitted()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroRhuEmpleadoCodigo', $form->get('txtCodigo')->getData());
                $session->set('filtroRhuEmpleadoNombre', $form->get('txtNombre')->getData());
                $session->set('filtroRhuEmpleadoIdentificacion', $form->get('txtIdentificacion')->getData());
                $session->set('filtroRhuEmpleadoEstadoContrato', $form->get('chkEstadoContrato')->getData());
            }
        }
        $arEmpleados = $paginator->paginate($em->getRepository(RhuEmpleado::class)->ListaBuscarEmpleado($raw), $request->query->get('page', 1), 20);
        return $this->render('turno/buscar/empleado.html.twig', array(
            'arEmpleados' => $arEmpleados,
            'campoCodigo' => $campoCodigo,
            'form' => $form->createView()
        ));
    }
}