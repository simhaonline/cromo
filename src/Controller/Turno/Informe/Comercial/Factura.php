<?php


namespace App\Controller\Turno\Informe\Comercial;


use App\Entity\General\GenCiudad;
use App\Entity\Transporte\TteCiudad;
use App\Entity\Turno\TurCliente;
use App\Entity\Turno\TurFactura;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class Factura extends  Controller
{
    /**
     * @Route("/turno/informe/comercial/factura/lista", name="turno_informe_comercial_factura_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtCodigoCliente', TextType::class,['required' => false])
            ->add('ciudad', EntityType::class, [
                'class' => GenCiudad::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre');
                },
                'choice_label' => 'nombre',
                'required' => false,
                'label' => 'Ciudad:',
                'attr' => ['class' => 'form-control to-select-2'],
                'placeholder' => 'TODOS',
            ])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroTurInformeComercialFacturaFechaDesde') ? date_create($session->get('filtroTurInformeComercialFacturaFechaDesde')): null])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false,  'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroTurInformeComercialFacturaFechaHasta') ? date_create($session->get('filtroTurInformeComercialFacturaFechaHasta')): null])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('autorizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'data' => $session->get('filtroTurInformeComercialFacturaAutorizado'), 'required' => false])
            ->add('anulado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'data' => $session->get('filtroTurInformeComercialFacturaAnulado'), 'required' => false])
            ->add('aprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'data' => $session->get('filtroTurInformeComercialFacturaAutorizado'), 'required' => false])

            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $arCliente = $em->getRepository(TurCliente::class)->find($form->get('txtCodigoCliente')->getData()??0);
                $arCiudad = $em->getRepository(GenCiudad::class)->find($form->get('ciudad')->getData()??0);

                if ($arCliente) {
                    $session->set('filtroTurInformeComercialFacturaClienteCodigo',  $arCliente->getCodigoClientePk());
                }
                if ($arCiudad) {
                    $session->set('filtroTurInformeComercialFacturaCiudad',  $arCiudad->getCodigoCiudadPk());
                }
                $session->set('filtroTurInformeComercialFacturaFechaDesde',  $form->get('fechaDesde')->getData() ?$form->get('fechaDesde')->getData()->format('Y-m-d'): null);
                $session->set('filtroTurInformeComercialFacturaFechaHasta', $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d'): null);
                $session->set('filtroTurInformeComercialFacturaAutorizado', $form->get('autorizado')->getData());
                $session->set('filtroTurInformeComercialFacturaAnulado', $form->get('anulado')->getData());
                $session->set('filtroTurInformeComercialFacturaAprobado', $form->get('aprobado')->getData());
            }
        }
        $arFacturas = $paginator->paginate($em->getRepository(TurFactura::class)->informe(), $request->query->getInt('page', 1), 30);
        return $this->render('turno/informe/comercial/Factura.html.twig', [
            'arFacturas' => $arFacturas,
            'form' => $form->createView()
        ]);
    }

}