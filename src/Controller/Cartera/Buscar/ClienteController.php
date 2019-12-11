<?php

namespace App\Controller\Cartera\Buscar;

use App\Entity\Cartera\CarCliente;
use App\Entity\Tesoreria\TesTercero;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;

class ClienteController extends AbstractController
{
   /**
    * @Route("/cartera/buscar/cliente/{campoCodigo}/{campoNombre}", name="cartera_buscar_cliente")
    */    
    public function lista(Request $request, PaginatorInterface $paginator,$campoCodigo, $campoNombre)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('txtNombre', TextType::class, array('label'  => 'Nombre','data' => $session->get('filtroCarNombreCliente')))
            ->add('txtCodigo', TextType::class, array('label'  => 'Codigo'))
            ->add('txtNit', TextType::class, array('label'  => 'Nit'))
            ->add('btnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();
        $form->handleRequest($request);
        $raw =[];
        if ($form->get('btnFiltrar')->isClicked()) {
            $raw['filtros']= [
                'codigoCliente' => $form->get('txtCodigo')->getData(),
                'nombre' =>  $form->get('txtNombre')->getData(),
                'identificacion' => $form->get('txtNit')->getData(),
            ];
        }
        $arClientes = $paginator->paginate($em->getRepository(CarCliente::class)->lista($raw), $request->query->getInt('page', 1),20);
        return $this->render('cartera/buscar/cliente.html.twig', array(
            'arClientes' => $arClientes,
            'campoCodigo' => $campoCodigo,
            'campoNombre' => $campoNombre,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/cartera/buscar/movimiento/cliente/{campoCodigo}", name="cartera_buscar_movimiento_cliente")
     */
    public function buscarClienteMovimiento(Request $request, $campoCodigo, PaginatorInterface $paginator)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('txtCodigo', TextType::class, ['required' => false, 'data' => $session->get('filtroCarCodigoCliente')])
            ->add('txtNit', TextType::class, array('label'  => 'Nit'))
            ->add('txtNombre', TextType::class, ['required' => false, 'data' => $session->get('filtroCarNombreCliente')])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar'])
            ->getForm();
        $form->handleRequest($request);
        $raw=[];
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $raw['filtros']= [
                    'codigoCliente' => $form->get('txtCodigo')->getData(),
                    'nombre' =>  $form->get('txtNombre')->getData(),
                ];
            }
        }
        $arClientes = $paginator->paginate($em->getRepository(CarCliente::class)->lista($raw), $request->query->get('page', 1), 20);
        return $this->render('cartera/buscar/buscarClienteMovimiento.html.twig', array(
            'arClientes' => $arClientes,
            'campoCodigo' => $campoCodigo,
            'form' => $form->createView()
        ));
    }

}

