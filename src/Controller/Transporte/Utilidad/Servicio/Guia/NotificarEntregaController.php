<?php

namespace App\Controller\Transporte\Utilidad\Servicio\Guia;

use App\Controller\MaestroController;
use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteNovedad;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\DateType;
class NotificarEntregaController extends MaestroController
{
    public $tipo = "proceso";
    public $proceso = "tteu0002";

   /**
    * @Route("/transporte/utilidad/control/guia/entrega", name="transporte_utilidad_servicio_guia_entrega")
    */    
    public function lista(Request $request, \Swift_Mailer $mailer, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();


        $form = $this->createFormBuilder()
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'widget' => 'single_text'])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'widget' => 'single_text'])
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnReportar', SubmitType::class, array('label' => 'Reportar'))
            ->getForm();
        $form->handleRequest($request);
        $arGuias = null;
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->get('btnFiltrar')->isClicked()) {
                    if($form->get('fechaDesde')->getData() && $form->get('fechaHasta')->getData()) {
                        $arGuias = $this->getDoctrine()->getRepository(TteGuia::class)->utilidadNotificarEntrega(
                            $form->get('fechaDesde')->getData()->format('Y-m-d'),
                            $form->get('fechaHasta')->getData()->format('Y-m-d'));
                    }
                }
                if ($form->get('btnReportar')->isClicked()) {
                    if($form->get('fechaDesde')->getData() && $form->get('fechaHasta')->getData()) {
                        $arrSeleccionados = $request->request->get('ChkSeleccionar');
                        if ($arrSeleccionados) {
                            foreach ($arrSeleccionados as $codigo) {
                                $arCliente = $em->getRepository(TteCliente::class)->find($codigo);
                                $destinatario = explode(';', strtolower($arCliente->getCorreo()));
                                $arGuias = $this->getDoctrine()->getRepository(TteGuia::class)->utilidadNotificarEntregaDetalle(
                                    $codigo,
                                    $form->get('fechaDesde')->getData()->format('Y-m-d'),
                                    $form->get('fechaHasta')->getData()->format('Y-m-d'));
                                $cuerpo = $this->render('transporte/utilidad/servicio/guia/correo.html.twig', [
                                    'arGuias' => $arGuias,
                                    'form' => $form->createView()]);
                                $message = (new \Swift_Message('Reporte entregas'))
                                    ->setFrom('infologicuartas@gmail.com')
                                    ->setTo($destinatario)
                                    ->setBody(
                                        $cuerpo,
                                        'text/html'
                                    );
                                $mailer->send($message);

                            }
                        }
                        $arGuias = $this->getDoctrine()->getRepository(TteGuia::class)->utilidadNotificarEntrega(
                            $form->get('fechaDesde')->getData()->format('Y-m-d'),
                            $form->get('fechaHasta')->getData()->format('Y-m-d'));
                    }
                }
            }
        }

        return $this->render('transporte/utilidad/servicio/guia/notificarEntrega.html.twig', [
            'arGuias' => $arGuias,
            'form' => $form->createView()]);
    }

}

