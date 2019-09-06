<?php

namespace App\Controller\Transporte\Informe\Transporte\Guia;

use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteGuia;
use App\General\General;
use App\Utilidades\Mensajes;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EstadoGuiasController extends Controller
{
    /**
     * @param Request $request
     * @param \Swift_Mailer $mailer
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/transporte/informe/transporte/guia/guias/estado", name="transporte_informe_transporte_guia_guias_estado")
     */
    public function lista(Request $request, \Swift_Mailer $mailer)
    {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $fecha = new \DateTime('now');
        if ($session->get('filtroFechaDesde') == "") {
            $session->set('filtroFechaDesde', $fecha->format('Y-m-d'));
        }
        if ($session->get('filtroFechaHasta') == "") {
            $session->set('filtroFechaHasta', $fecha->format('Y-m-d'));
        }
        $arGuias = null;
        $form = $this->createFormBuilder()
            ->add('btnEnviar', SubmitType::class, array('label' => 'Enviar correo'))
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'data' => date_create($session->get('filtroTteFechaDesde'))])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'data' => date_create($session->get('filtroTteFechaHasta'))])
            ->add('txtCodigoCliente', TextType::class, ['required' => false, 'data' => $session->get('filtroTteCodigoCliente'), 'attr' => ['class' => 'form-control']])
            ->add('txtNombreCorto', TextType::class, ['required' => false, 'data' => $session->get('filtroTteNombreCliente'), 'attr' => ['class' => 'form-control', 'readonly' => 'reandonly']])
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->get('btnFiltrar')->isClicked() || $form->get('btnExcel')->isClicked() || $form->get('btnEnviar')->isClicked()) {
                    $session = new session;
                    $session->set('filtroTteFechaDesde', $form->get('fechaDesde')->getData()->format('Y-m-d'));
                    $session->set('filtroTteFechaHasta', $form->get('fechaHasta')->getData()->format('Y-m-d'));
                    $session->set('filtroTteCodigoCliente', $form->get('txtCodigoCliente')->getData());
                    $session->set('filtroTteNombreCliente', $form->get('txtNombreCorto')->getData());
                    if ($form->get('txtCodigoCliente')->getData() != '') {
                        $arGuias = $em->getRepository(TteGuia::class)->estadoGuia()->getQuery()->getResult();
                    }
                }
                if ($form->get('btnExcel')->isClicked()) {
                    General::get()->setExportar($em->getRepository(TteGuia::class)->estadoGuia()->getQuery()->getResult(), "Guias cliente");
                }
                if ($form->get('btnEnviar')->isClicked()) {
                    $codigoCliente = $form->get('txtCodigoCliente')->getData();
                    if ($codigoCliente != "") {
                        $arCliente = $em->getRepository(TteCliente::class)->find($codigoCliente);
                        if ($arCliente) {
                            $correo = strtolower($arCliente->getCorreo());
                            if ($correo) {
                                $pos = strpos($correo, ",");
                                if ($pos === false) {
                                    $destinatario = explode(';', $correo);
                                    $arGuias = $this->getDoctrine()->getRepository(TteGuia::class)->estadoGuia($codigoCliente)->getQuery()->getResult();
                                    $cuerpo = $this->render('transporte/informe/transporte/guia/correo.html.twig', [
                                        'arGuias' => $arGuias,
                                        'form' => $form->createView()]);
                                    $message = (new \Swift_Message('Guias cliente'))
                                        ->setFrom('infologicuartas@gmail.com')
                                        ->setTo($destinatario)
                                        ->setBody(
                                            $cuerpo,
                                            'text/html'
                                        );
                                    $mailer->send($message);
                                } else {
                                    Mensajes::error("El correo del cliente " . $correo . " contiene carcteres invalidos");
                                }
                            } else {
                                Mensajes::error("El cliente no tiene correo asignado");
                            }
                        }
                        $arGuias = $this->getDoctrine()->getRepository(TteGuia::class)->estadoGuia($codigoCliente);
                    }
                }
            }
        }

        return $this->render('transporte/informe/transporte/guia/estadoGuias.html.twig', [
            'arGuias' => $arGuias,
            'form' => $form->createView()]);
    }


}

