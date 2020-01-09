<?php


namespace App\Controller\Tesoreria\Utilidad\Movimiento;


use App\Entity\Tesoreria\TesMovimientoClase;
use App\Formato\Cartera\ReciboMasivo;
use App\Formato\Tesoreria\Compra;
use App\Formato\Tesoreria\Egreso;
use App\Formato\Tesoreria\NotaCredito;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MovimientoMasivoController extends AbstractController
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("/tesoreria/utilidad/movimiento/formatomovimientomasivo/lista", name="tesoreria_utilidad_movimiento_formatomovimientomasivo_lista")
     */
    public function lista(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('movimientoClaseRel', EntityType::class, [
                'class' => TesMovimientoClase::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('mtc')
                        ->orderBy('mtc.nombre', 'ASC');
                },
                'required' => true,
                'choice_label' => 'nombre',
                'attr' => ['class' => 'form-control to-select-2']
            ])
            ->add('codigoMovimientoDesde', TextType::class, array('required' => false))
            ->add('codigoMovimientoHasta', TextType::class, array('required' => false))
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('btnGenerar', SubmitType::class, ['label' => 'Generar', 'attr' => ['class' => 'btn btn-default btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGenerar')->isClicked()) {
                $arrDatos = [
                    'codigoMovimientoPk' => null,
                    'codigoMovimientoDesde' => $form->get('codigoMovimientoDesde')->getData(),
                    'codigoMovimientoHasta' => $form->get('codigoMovimientoHasta')->getData(),
                    'codigoMovimientoClase' => $form->get('movimientoClaseRel')->getData()->getCodigoMovimientoClasePk(),
                    'fechaDesde' => $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null,
                    'fechaHasta' => $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null
                ];
                if ($form->get('movimientoClaseRel')->getData()->getCodigoMovimientoClasePk() == 'EG') {
                    $objFormatoPago = new Egreso();
                    $objFormatoPago->Generar($em, $arrDatos);
                } elseif ($form->get('movimientoClaseRel')->getData()->getCodigoMovimientoClasePk() == 'CP') {
                    $objFormatoPago = new Compra();
                    $objFormatoPago->Generar($em, $arrDatos);
                } elseif ($form->get('movimientoClaseRel')->getData()->getCodigoMovimientoClasePk() == 'NC') {
                    $objFormatoPago = new NotaCredito();
                    $objFormatoPago->Generar($em, $arrDatos);
                }
            }
        }
        return $this->render('tesoreria/utilidad/movimiento/movimientoMasivo.html.twig', [
            'form' => $form->createView()
        ]);
    }
}