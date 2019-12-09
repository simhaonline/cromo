<?php


namespace App\Controller\Turno\Informe\Comercial;

use App\Entity\General\GenCiudad;
use App\Entity\Transporte\TteCiudad;
use App\Entity\Turno\TurCliente;
use App\Entity\Turno\TurFacturaDetalle;
use App\General\General;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class FacturaDetalle extends  AbstractController
{
    /**
     * @Route("/turno/informe/comercial/facturaDetalle/lista", name="turno_informe_comercial_facturaDetalle_lista")
     */

    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('txtCodigoCliente', TextType::class,['required' => false])
            ->add('numero', IntegerType::class, ['required' => false,'data' => $session->get('filtroGenFacturaNumero')])
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
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroInvInformeAsesorVentasFechaDesde') ? date_create($session->get('filtroInvInformeAsesorVentasFechaDesde')): null])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false,  'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroInvInformeAsesorVentasFechaHasta') ? date_create($session->get('filtroInvInformeAsesorVentasFechaHasta')): null])
            ->add('autorizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'ANULADO' => '1', 'SIN ANULAR' => '0'], 'data' => $session->get('filtroTteNovedadEstadoAtendido'), 'required' => false])
            ->add('anulado', ChoiceType::class, ['choices' => ['TODOS' => '', 'ANULADO' => '1', 'SIN ANULAR' => '0'], 'data' => $session->get('filtroTteNovedadEstadoAtendido'), 'required' => false])

            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add( 'btnExcel', SubmitType::class, ['label'=>'Excel', 'attr'=>['class'=> 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $arCliente = $em->getRepository(TurCliente::class)->find($form->get('txtCodigoCliente')->getData()??0);
                $arCiudad = $em->getRepository(TteCiudad::class)->find($form->get('ciudad')->getData()??0);
                if ($arCliente) {
                    $session->set('filtroTurInformeComercialFacturaDetalleClienteCodigo',  $arCliente->getCodigoClientePk());
                }
                if ($arCiudad) {
                    $session->set('filtroTurInformeComercialFacturaDetalleCiudad',  $arCiudad->getCodigoCiudadPk());
                }
                $session->set('filtroTurInformeComercialFacturaDetalleFechaDesde',  $form->get('fechaDesde')->getData() ?$form->get('fechaDesde')->getData()->format('Y-m-d'): null);
                $session->set('filtroTurInformeComercialFacturaDetalleFechaHasta', $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d'): null);
                $session->set('filtroTurInformeComercialFacturaDetalleAutorizado', $form->get('autorizado')->getData());
                $session->set('filtroTurInformeComercialFacturaDetalleAnulado', $form->get('anulado')->getData());
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->createQuery($em->getRepository(TurFacturaDetalle::class)->informe())->execute(), "Facturas detalle");

            }
        }
        $arFacturaDetalles = $paginator->paginate($em->getRepository(TurFacturaDetalle::class)->informe(), $request->query->getInt('page', 1), 30);
        return $this->render('turno/informe/comercial/FacturaDetalle.html.twig', [
            'arFacturaDetalles' => $arFacturaDetalles,
            'form' => $form->createView()
        ]);
    }
}