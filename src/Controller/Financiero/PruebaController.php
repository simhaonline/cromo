<?php

namespace App\Controller\Financiero;

use App\Controller\Estructura\MensajesController;
use App\Entity\Financiero\FinCuenta;
use App\Entity\Financiero\FinRegistro;
use App\Entity\Financiero\FinSaldo;
use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteFacturaTipo;
use App\Formato\Transporte\ControlFactura;
use App\Formato\Transporte\FacturaInforme;
use App\General\General;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Response;
class PruebaController extends Controller
{
    /**
     * @Route("/financiero/generar/saldo", name="financiero_generar_saldo")
     */
    public function lista(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $arRegistros = $em->createQueryBuilder()->from(FinRegistro::class, 'r')
            ->select('r.codigoCuentaFk')
            ->addSelect('SUM(r.vrDebito) as vrDebito')
            ->addSelect('SUM(r.vrCredito) as vrCredito')
            ->addGroupBy('r.codigoCuentaFk')
            ->getQuery()->getResult();
        foreach ($arRegistros as $arRegistro) {
            $arSaldo = new FinSaldo();
            $arCuenta = $em->getRepository(FinCuenta::class)->find($arRegistro['codigoCuentaFk']);
            $arSaldo->setCuentaRel($arCuenta);
            $arSaldo->setVrCredito($arRegistro['vrCredito']);
            $arSaldo->setVrDebito($arRegistro['vrDebito']);
            $em->persist($arSaldo);
        }
        $em->flush();

        return new Response(
            "<html><body>Hola</body></html>"
        );
    }
}

