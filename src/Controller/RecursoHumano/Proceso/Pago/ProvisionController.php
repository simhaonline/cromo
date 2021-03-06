<?php

namespace App\Controller\RecursoHumano\Proceso\Pago;

use App\Controller\MaestroController;
use App\Entity\Inventario\InvLote;
use App\Entity\Inventario\InvMovimientoDetalle;
use App\Entity\Inventario\InvOrdenCompraDetalle;
use App\Entity\Inventario\InvOrdenDetalle;
use App\Entity\Inventario\InvPedidoDetalle;
use App\Entity\Inventario\InvRemisionDetalle;
use App\Entity\Inventario\InvSolicitudDetalle;
use App\Entity\RecursoHumano\RhuPago;
use App\Utilidades\Mensajes;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProvisionController extends MaestroController
{


    public $tipo = "proceso";
    public $proceso = "rhup0002";





    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/recursohumano/proceso/pago/regenerarprovision", name="recursohumano_proceso_pago_regenerarprovision")
     */
    public function lista(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigo', TextType::class, array('required' => false))
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' =>  new \DateTime('now')])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false,  'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => new \DateTime('now')])
            ->add('btnRegenerar', SubmitType::class, ['label' => 'Regenerar provision', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnRegenerar')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $fechaDesde = $form->get('fechaDesde')->getData();
                $fechaHasta = $form->get('fechaHasta')->getData();
                $codigo = $form->get('codigo')->getData();
                $em->getRepository(RhuPago::class)->regenerarProvision($fechaDesde->format('Y-m-d'), $fechaHasta->format('Y-m-d'), $codigo);
                Mensajes::success("Proceso terminado");
            }
        }
        return $this->render('recursohumano/proceso/pago/regenerarProvision.html.twig', [
            'form' => $form->createView()
        ]);
    }

}