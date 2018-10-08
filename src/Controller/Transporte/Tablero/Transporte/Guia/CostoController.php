<?php

namespace App\Controller\Transporte\Tablero\Transporte\Guia;


use App\Entity\Transporte\TteCosto;
use App\Entity\Transporte\TteGuia;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CostoController extends Controller
{
   /**
    * @Route("/transporte/tablero/transporte/guia/costo", name="transporte_tablero_transporte_guia_costo")
    */    
    public function principal(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = new Session();
        $form = $this->createFormBuilder()
            ->add('chkMes', ChoiceType::class, ['choices' => ['ENERO' => '1', 'FEBRERO' => '2', 'MARZO' => '3', 'ABRIL' => '4', 'MAYO' => '5', 'JUNIO' => '6', 'JULIO' => '7', 'AGOSTO' => '8', 'SEPTIEMBRE' => '9', 'OCTUBRE' => '10', 'NOVIEMBRE' => '11', 'DICIEMBRE' => '12'], 'data' => $session->get('filtroTteInformeCostoMes'), 'required' => true])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if($form->get('btnFiltrar')->isClicked()){
                $session->set('filtroTteInformeCostoMes', $form->get('chkMes')->getData());
            }
        }
        $arrCostoMesCliente = $em->getRepository(TteCosto::class)->costosPorMes(1);
        $arrCostoMesDestino = $em->getRepository(TteCosto::class)->costosPorMes(2);

        $arrCategoriasClientes = array_map(
            function ($clientes){
                return $clientes['nombreCorto'];
            },$arrCostoMesCliente
        );
        $arrCategoriasDestino = array_map(
            function ($destinos){
                return $destinos['nombre'];
            },$arrCostoMesDestino
        );
        $arrCostosCliente = array_map(
            function ($costos){
                return intval($costos['vrCosto']);
            }, $arrCostoMesCliente
        );
        $arrPreciosCliente = array_map(
            function ($precios){
                return intval($precios['vrPrecio']);
            }, $arrCostoMesCliente
        );
        $arrCostosDestino = array_map(
            function ($costos){
                return intval($costos['vrCosto']);
            }, $arrCostoMesDestino
        );
        $arrPreciosDestino = array_map(
            function ($precios){
                return intval($precios['vrPrecio']);
            }, $arrCostoMesDestino
        );
        $seriesClientes = [
            [
                "name" => "Costo",
                "data" => $arrCostosCliente,
                "dataLabels" => [
                    "enabled" => true,
                    "rotation" => -90,
                    "color" => "#FFFFFF",
                    "align" => "right",
                    "y" => 10,
                    "style" => [
                        "fontSize" => "13px",
                        "fontFamily" => "Verdana, sans-serif"]
                ]],
            [
                "name" => "Precio",
                "data" => $arrPreciosCliente,
                "dataLabels" => [
                    "enabled" => true,
                    "rotation" => -90,
                    "color" => "#FFFFFF",
                    "align" => "right",
                    "y" => 10,
                    "style" => [
                        "fontSize" => "13px",
                        "fontFamily" => "Verdana, sans-serif"]
                ]],

        ];
        $seriesDestino = [
            [
                "name" => "Costo",
                "data" => $arrCostosDestino,
                "dataLabels" => [
                    "enabled" => true,
                    "rotation" => -90,
                    "color" => "#FFFFFF",
                    "align" => "right",
                    "y" => 10,
                    "style" => [
                        "fontSize" => "13px",
                        "fontFamily" => "Verdana, sans-serif"]
                ]],
            [
                "name" => "Precio",
                "data" => $arrPreciosDestino,
                "dataLabels" => [
                    "enabled" => true,
                    "rotation" => -90,
                    "color" => "#FFFFFF",
                    "align" => "right",
                    "y" => 10,
                    "style" => [
                        "fontSize" => "13px",
                        "fontFamily" => "Verdana, sans-serif"]
                ]],

        ];

        $ob = new Highchart();
        $ob->chart->renderTo('container');
        $ob->chart->type('column');
        $ob->title->text('Costos y precios por cliente');
        $ob->xAxis->title(array('text' => "Cliente"));
        $ob->xAxis->categories($arrCategoriasClientes);
        $ob->yAxis->title(array('text' => "Costos y precios"));
        $ob->series($seriesClientes);

        $ob1 = new Highchart();
        $ob1->chart->renderTo('container2');
        $ob1->chart->type('column');
        $ob1->title->text('Costos y precios por destino');
        $ob1->xAxis->title(array('text' => "Destino"));
        $ob1->xAxis->categories($arrCategoriasDestino);
        $ob1->yAxis->title(array('text' => "Costos y precios"));
        $ob1->series($seriesDestino);

        return $this->render('transporte/tablero/transporte/guia/costos.html.twig', [
            'arrCostosMesCliente' => $arrCostoMesCliente,
            'arrCostosMesDestino' => $arrCostoMesDestino,
            'form' => $form->createView(),
            'chart' => $ob,
            'chartDestino' => $ob1]);
    }
}

