<?php

namespace App\Controller\Turno\Buscar;

use App\Entity\Turno\TurCliente;
use App\Entity\Turno\TurPuesto;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PuestoController extends AbstractController
{
    /**
     * @param Request $request
     * @param $campoCodigo
     * @param $campoNombre
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/turno/buscar/puesto/{campoCodigo}/{campoNombre}", name="turno_buscar_puesto")
     */
    public function lista(Request $request, PaginatorInterface $paginator,$campoCodigo, $campoNombre)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('txtNombre', TextType::class, array('required' => false, 'data' => $session->get('filtroTurPuestoNombreCliente')))
            ->add('txtCodigo', TextType::class, array('required' => false, 'data' => $session->get('filtroTurPuestoCodigoPuesto')))
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroTurPuestoCodigoPuesto', $form->get('txtCodigo')->getData());
                $session->set('filtroTurPuestoNombreCliente', $form->get('txtNombre')->getData());
            }
        }
        $arPuestos = $paginator->paginate($em->getRepository(TurPuesto::class)->lista(), $request->query->get('page', 1), 20);
        return $this->render('turno/buscar/puesto.html.twig', array(
            'arPuestos' => $arPuestos,
            'campoCodigo' => $campoCodigo,
            'campoNombre' => $campoNombre,
            'form' => $form->createView()
        ));
    }
}

