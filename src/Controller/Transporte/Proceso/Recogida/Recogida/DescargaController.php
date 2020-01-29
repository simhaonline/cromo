<?php

namespace App\Controller\Transporte\Proceso\Recogida\Recogida;

use App\Controller\MaestroController;
use App\Entity\Transporte\TteRecogida;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DescargaController extends MaestroController
{

    public $tipo = "proceso";
    public $proceso = "ttep0003";



    /**
    * @Route("/transporte/proceso/recogida/recogida/descarga", name="transporte_proceso_recogida_recogida_descarga")
    */    
    public function lista(Request $request, PaginatorInterface $paginator)
    {

        $query = $this->getDoctrine()->getRepository(TteRecogida::class)->findBy(array('codigoRecogidaPk' => NULL));
        $form = $this->createFormBuilder()
            ->add('codigoDespachoRecogida', TextType::class)
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnDescarga', SubmitType::class, array('label' => 'Descarga'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $codigoDespachoRecogida = $form->get('codigoDespachoRecogida')->getData();
                $query = $this->getDoctrine()->getRepository(TteRecogida::class)->listaDescarga($codigoDespachoRecogida);
            }
            if ($form->get('btnDescarga')->isClicked()) {
                $arrRecogidas = $request->request->get('ChkSeleccionar');
                $arrControles = $request->request->All();
                $respuesta = $this->getDoctrine()->getRepository(TteRecogida::class)->descarga($arrRecogidas, $arrControles);
                $codigoDespachoRecogida = $form->get('codigoDespachoRecogida')->getData();
                $query = $this->getDoctrine()->getRepository(TteRecogida::class)->listaDescarga($codigoDespachoRecogida);
            }
        }
        $arRecogidas = $paginator->paginate($query, $request->query->getInt('page', 1),10);
        return $this->render('transporte/proceso/recogida/recogida/descarga.html.twig', [
            'arRecogidas' => $arRecogidas,
            'form' => $form->createView()]);
    }
}

