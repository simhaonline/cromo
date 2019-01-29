<?php

namespace App\Controller\Transporte\Informe\Transporte\Guia;

use App\Entity\Transporte\TteGuia;
use App\Formato\Transporte\PendienteDespachoRuta;
use App\General\General;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class PendienteDespachoRutaController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/transporte/inf/transporte/guia/pendientedespachoruta", name="transporte_inf_transporte_guia_pendiente_despacho_ruta")
     */
    public function lista(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->get('BtnFiltrar')->isClicked()) {
                    $this->filtrar($form);
                    $form = $this->formularioFiltro();
                }
                if ($form->get('btnPdf')->isClicked()) {
                    $formato = new PendienteDespachoRuta();
                    $formato->Generar($em);
                }
                if ($form->get('btnExcel')->isClicked()) {
                    General::get()->setExportar($em->getRepository(TteGuia::class)->informeDespachoPendienteRuta(), "Pendiente despacho ruta");
                }
            }
        }
        $arGuias = $this->getDoctrine()->getRepository(TteGuia::class)->informeDespachoPendienteRuta();
        return $this->render('transporte/informe/transporte/guia/despachoPendienteRuta.html.twig', [
            'arGuias' => $arGuias,
            'form' => $form->createView()]);
    }

    private function filtrar($form)
    {
        $session = new session;
        $arRuta = $form->get('rutaRel')->getData();
        if ($arRuta) {
            $session->set('filtroTteCodigoRuta', $arRuta->getCodigoRutaPk());
        } else {
            $session->set('filtroTteCodigoRuta', null);
        }
        $arServicio = $form->get('servicioRel')->getData();
        if ($arServicio) {
            $session->set('filtroTteCodigoServicio', $arServicio->getCodigoServicioPk());
        } else {
            $session->set('filtroTteCodigoServicio', null);
        }
        $arOperacionCargo = $form->get('operacionCargoRel')->getData();
        if ($arOperacionCargo) {
            $session->set('filtroTteCodigoOperacionCargo', $arOperacionCargo->getCodigoOperacionPk());
        } else {
            $session->set('filtroTteCodigoOperacionCargo', null);
        }
        $session->set('filtroTteMostrarDevoluciones', $form->get('ChkMostrarDevoluciones')->getData());
        $session->set('filtroTteNovedadGuia', $form->get('chkEstadoGuiaNovedad')->getData());
        if ($form->get('txtCodigoCliente')->getData() != '') {
            $session->set('filtroTteCodigoCliente', $form->get('txtCodigoCliente')->getData());
            $session->set('filtroTteNombreCliente', $form->get('txtNombreCorto')->getData());
        } else {
            $session->set('filtroTteCodigoCliente', null);
            $session->set('filtroTteNombreCliente', null);
        }
    }

    private function formularioFiltro()
    {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $arrayPropiedadesRuta = array(
            'class' => 'App\Entity\Transporte\TteRuta',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('r')
                    ->orderBy('r.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        );
        if ($session->get('filtroTteCodigoRuta')) {
            $arrayPropiedadesRuta['data'] = $em->getReference("App\Entity\Transporte\TteRuta", $session->get('filtroTteCodigoRuta'));
        }
        $arrayPropiedadesServicio = array(
            'class' => 'App\Entity\Transporte\TteServicio',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('s')
                    ->orderBy('s.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        );
        if ($session->get('filtroTteCodigoServicio')) {
            $arrayPropiedadesServicio['data'] = $em->getReference("App\Entity\Transporte\TteServicio", $session->get('filtroTteCodigoServicio'));
        }

        $arrayPropiedadesOperacionCargo = array(
            'class' => 'App\Entity\Transporte\TteOperacion',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('o')
                    ->orderBy('o.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        );
        if ($session->get('filtroTteCodigoOperacionCargo')) {
            $arrayPropiedadesOperacionCargo['data'] = $em->getReference("App\Entity\Transporte\TteOperacion", $session->get('filtroTteCodigoOperacionCargo'));
        }

        $form = $this->createFormBuilder()
            ->add('txtCodigoCliente', TextType::class, ['required' => false, 'data' => $session->get('filtroTteCodigoCliente'), 'attr' => ['class' => 'form-control']])
            ->add('txtNombreCorto', TextType::class, ['required' => false, 'data' => $session->get('filtroTteNombreCliente'), 'attr' => ['class' => 'form-control', 'readonly' => 'reandonly']])
            ->add('ChkMostrarDevoluciones', CheckboxType::class, array('label' => false, 'required' => false, 'data' => $session->get('filtroTteMostrarDevoluciones')))
            ->add('chkEstadoGuiaNovedad', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'data' => $session->get('filtroTteNovedadGuia'), 'required' => false])
            ->add('rutaRel', EntityType::class, $arrayPropiedadesRuta)
            ->add('servicioRel', EntityType::class, $arrayPropiedadesServicio)
            ->add('operacionCargoRel', EntityType::class, $arrayPropiedadesOperacionCargo)
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnPdf', SubmitType::class, array('label' => 'Pdf'))
            ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->getForm();
        return $form;
    }


}

