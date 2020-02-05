<?php


namespace App\Controller\Cartera\utilidad\recibos;


use App\Controller\MaestroController;
use App\Formato\Cartera\ReciboMasivo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FormatoReciboMasivoController extends MaestroController
{
    public $tipo = "proceso";
    public $proceso = "caru0001";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("/cartera/utilidad/recibos/formatorecibosmasivo/lista", name="cartera_utilidad_resibos_formatorecibosmasivo_lista")
     */
    public function lista(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoReciboDesde', TextType::class, array('required' => false))
            ->add('codigoReciboHasta', TextType::class, array('required' => false))
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false,  'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('btnGenerar', SubmitType::class, ['label' => 'Generar', 'attr' => ['class' => 'btn btn-default btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGenerar')->isClicked()) {
                $arrDatos = [
                    'codigoReciboDesde' => $form->get('codigoReciboDesde')->getData(),
                    'codigoReciboHasta' => $form->get('codigoReciboHasta')->getData(),
                    'fechaDesde' => $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null,
                    'fechaHasta' => $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null
                ];
                $objFormatoPago = new ReciboMasivo();
                $objFormatoPago->Generar($em, $arrDatos);
            }
        }
        return $this->render('cartera/utilidad/recibos/reciboMasivo.html.twig', [
            'form' => $form->createView()
        ]);
    }
}