<?php

namespace App\Controller\Crm\General;

use App\Entity\Crm\CrmNegocio;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class InicioController extends AbstractController
{
    /**
     * @Route("/crm/general/inicio", name="crm_general_general_inicio_ver")
     */
    public function inicio()
    {
        $em = $this->getDoctrine()->getManager();
        // Chart
        $data=$em->getRepository(CrmNegocio::class)->GraficaNegociosporFace();
        $ob = new Highchart();
        $ob->chart->renderTo('piechart');
        $ob->title->text('Chart Title');
        $ob->title->text('Browser market shares at a specific website in 2010');
        $ob->plotOptions->pie(array(
            'allowPointSelect'  => true,
            'cursor'    => 'pointer',
            'dataLabels'    => array('enabled' => false),
            'showInLegend'  => true
        ));
//        $data = array(
//            array('Firefox', 45.0),
//            array('IE', 26.8),
//            array('Chrome', 12.8),
//            array('Safari', 8.5),
//            array('Opera', 6.2),
//            array('Others', 0.7),
//        );
        $ob->series(array(array('type' => 'pie','name' => 'Browser share', 'data' => $data)));
        return $this->render('crm/general/general.html.twig', [
            'chart' => $ob
        ]);
    }
}
