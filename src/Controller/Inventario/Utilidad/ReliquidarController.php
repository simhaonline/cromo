<?php

namespace App\Controller\Inventario\Utilidad;

use App\Controller\MaestroController;
use App\Entity\Inventario\InvMovimiento;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ReliquidarController extends MaestroController
{


    public $tipo = "utilidad";
    public $proceso = "invu0003";




    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/inventario/utilidad/inventario/reliquidar/lista", name="inventario_utilidad_inventario_reliquidar_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('txtCodigoMovimiento', TextType::class, ['required' => true])
            ->add('btnReliquidar', SubmitType::class, ['label' => 'Re-liquidar','attr' => ['class' => 'btn btn-default btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnReliquidar')->isClicked()) {
                if($form->get('txtCodigoMovimiento')->getData() != ''){
                    $em->getRepository(InvMovimiento::class)->liquidar($em->getRepository(InvMovimiento::class)->find($form->get('txtCodigoMovimiento')->getData()));
                }
            }
        }
        return $this->render('inventario/utilidad/reliquidar.html.twig', [
            'form' => $form->createView()
        ]);
    }
}