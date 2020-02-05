<?php


namespace App\Controller\RecursoHumano\Informe\Nomina;


use App\Controller\MaestroController;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use App\Utilidades\Mensajes;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CreditoPago extends  MaestroController
{

    public $tipo = "proceso";
    public $proceso = "rhui0005";

    /**
     * @Route("/recursohumano/informe/nomina/creditoPago/lista", name="recursohumano_informe_nomina_credito_pago")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $fechaActual = new \DateTime('now');
        $form = $this->createFormBuilder()
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'data'=> $fechaActual,'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'data'=>$fechaActual,'required' => false,  'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('BtnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-default btn-default']])
            ->setMethod('GET')
            ->getForm();
        $form->handleRequest($request);
        $raw = null;
        if ($form->isSubmitted() && $form->isValid()) {
            $respuesta = "";
            if ($form->get('BtnFiltrar')->isClicked()) {
                $fechasDesde = $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null;
                $fechasHasta = $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null;
                if ($fechasDesde == '') {
                    $respuesta = 'Debe ingresar una fecha desde';
                } elseif ($fechasHasta == '') {
                    $respuesta = 'Debe ingresar un fecha hasta';
                } else {
                    $raw = [
                        'fechaDesde' => $fechasDesde,
                        'fechaHasta' => $fechasHasta
                    ];
                }
                if ($respuesta == '') {
                    $arCreditos = $paginator->paginate($em->getRepository(RhuPagoDetalle::class)->creditosPago($raw), $request->query->getInt('page', 1), 30);
                    if (count($arCreditos) == 0) {
                        Mensajes::error('No se encontraron pagos con creditos entre las fechas ingresadas');
                    }
                } else {
                    Mensajes::error($respuesta);
                }
            }
        }
        $arPagoDetalles = $paginator->paginate($em->getRepository(RhuPagoDetalle::class)->creditosPago($raw), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/informe/nomina/creditoPago/lista.html.twig', [
            'arPagoDetalles' => $arPagoDetalles,
            'form' => $form->createView(),
        ]);
    }
}