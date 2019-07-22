<?php

namespace App\Controller\RecursoHumano\Utilidad\Pago;

use App\Entity\Inventario\InvMovimiento;
use App\Entity\RecursoHumano\RhuConfiguracion;
use App\Entity\RecursoHumano\RhuPagoTipo;
use App\Formato\RecursoHumano\PagoMasivo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FormatoPagoMasivoController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("/recursohumano/utilidad/pago/formatopagomasivo/lista", name="recursohumano_utilidad_pago_formatopagomasivo_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $arrayPropiedadesTipo = array(
            'class' => RhuPagoTipo::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        );
        if ($session->get('filtroCodigoPagoTipo')) {
            $arrayPropiedadesTipo['data'] = $em->getReference(RhuPagoTipo::class, $session->get('filtroCodigoPagoTipo'));
        }
        $dateFecha = new \DateTime('now');
        $strFechaDesde = $dateFecha->format('Y/m/') . "01";
        $intUltimoDia = $strUltimoDiaMes = date("d", (mktime(0, 0, 0, $dateFecha->format('m') + 1, 1, $dateFecha->format('Y')) - 1));
        $strFechaHasta = $dateFecha->format('Y/m/') . $intUltimoDia;
        if ($session->get('filtroFormatoMasivoFechaDesde') != "") {
            $strFechaDesde = $session->get('filtroFormatoMasivoFechaHasta');
        }
        if ($session->get('filtroFormatoMasivoFechaHasta') != "") {
            $strFechaHasta = $session->get('filtroFormatoMasivoFechaHasta');
        }
        $dateFechaDesde = date_create($strFechaDesde);
        $dateFechaHasta = date_create($strFechaHasta);
        $form = $this->createFormBuilder()
            ->add('pagoTipoRel', EntityType::class, $arrayPropiedadesTipo)
            ->add('numero', IntegerType::class, array('required' => false, 'data' => 0))
            ->add('fechaDesde', DateType::class, array('format' => 'yyyyMMdd', 'data' => $dateFechaDesde))
            ->add('fechaHasta', DateType::class, array('format' => 'yyyyMMdd', 'data' => $dateFechaHasta))
            ->add('porFecha', CheckboxType::class, array('required' => false, 'data' => true))
            ->add('btnGenerar', SubmitType::class, ['label' => 'Generar','attr' => ['class' => 'btn btn-default btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGenerar')->isClicked()) {
                $arPagoTipo = $form->get('pagoTipoRel')->getData();
                if ($arPagoTipo) {
                    $codigoPagoTipo = $arPagoTipo->getCodigoPagoTipoPk();
                } else {
                    $codigoPagoTipo = "";
                }
                $fechaDesde = $form->get('fechaDesde')->getData();
                $fechaHasta = $form->get('fechaHasta')->getData();
                $objFormatoPago = new PagoMasivo();
                $objFormatoPago->Generar($em, $form->get('numero')->getData(), "", "", $form->get('porFecha')->getData(), $fechaDesde->format('Y-m-d'), $fechaHasta->format('Y-m-d'),  $codigoPagoTipo);
            }
        }
        return $this->render('recursohumano/utilidad/pago/pagomasivo.html.twig', [
            'form' => $form->createView()
        ]);
    }
}