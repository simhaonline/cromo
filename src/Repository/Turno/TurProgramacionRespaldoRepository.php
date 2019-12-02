<?php

namespace App\Repository\Turno;


use App\Controller\Estructura\FuncionesController;
use App\Entity\Crm\CrmVisita;
use App\Entity\Turno\TurConcepto;
use App\Entity\Turno\TurContratoDetalle;
use App\Entity\Turno\TurFestivo;
use App\Entity\Turno\TurPedido;
use App\Entity\Turno\TurPedidoDetalle;
use App\Entity\Turno\TurProgramacion;
use App\Entity\Turno\TurProgramacionRespaldo;
use App\Entity\Turno\TurPrototipo;
use App\Entity\Turno\TurTurno;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TurProgramacionRespaldoRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TurProgramacionRespaldo::class);
    }

    public function generar($arSoporte, $arSoporteContrato)
    {
        $anio = $arSoporte->getFechaDesde()->format('Y');
        $mes = $arSoporte->getFechaDesde()->format('m');
        $em = $this->getEntityManager();
        $arrProgramacion = array();
        $dql = "SELECT pd.dia1, pd.dia2, pd.dia3, pd.dia4, pd.dia5, pd.dia6, pd.dia7, pd.dia8, pd.dia9, pd.dia10, pd.dia11, pd.dia12, pd.dia13, pd.dia14, pd.dia15, pd.dia16, pd.dia17, pd.dia18, pd.dia19, pd.dia20, pd.dia21, pd.dia22, pd.dia23, pd.dia24, pd.dia25, pd.dia26, pd.dia27, pd.dia28, pd.dia29, pd.dia30, pd.dia31 FROM App\Entity\Turno\TurProgramacion pd WHERE pd.anio = " . $anio . " AND pd.mes = " . $mes . " AND pd.codigoEmpleadoFk = " . $arSoporteContrato['codigoEmpleadoFk'];
        $query = $em->createQuery($dql);
        $arResultados = $query->getResult();

        foreach ($arResultados as $arResultado) {
            for ($j = 1; $j <= 31; $j++) {
                if ($arResultado['dia' . $j]) {
                    if (isset($arrProgramacion[1][$j])) {
                        if (!$arrProgramacion[1][$j]) {
                            $arrProgramacion[1][$j] = $arResultado['dia' . $j];
                        }
                    } else {
                        $arrProgramacion[1][$j] = $arResultado['dia' . $j];
                    }
                } else {
                    if (isset($arrProgramacion[1][$j])) {
                        if (!$arrProgramacion[1][$j]) {
                            $arrProgramacion[1][$j] = null;
                        }
                    } else {
                        $arrProgramacion[1][$j] = null;
                    }

                }
            }

        }

        foreach ($arrProgramacion as $detalle) {
            $arProgramacionRespaldo = new TurProgramacionRespaldo();
            $arProgramacionRespaldo->setCodigoSoporteFk($arSoporte->getCodigoSoportePk());
            $arProgramacionRespaldo->setCodigoSoporteContratoFk($arSoporteContrato['codigoSoporteContratoPk']);
            $arProgramacionRespaldo->setCodigoEmpleadoFk($arSoporteContrato['codigoEmpleadoFk']);
            $arProgramacionRespaldo->setAnio($anio);
            $arProgramacionRespaldo->setMes($mes);
            for ($j = 1; $j <= 31; $j++) {
                if ($j == 1) {
                    $arProgramacionRespaldo->setDia1($detalle[$j]);
                }
                if ($j == 2) {
                    $arProgramacionRespaldo->setDia2($detalle[$j]);
                }
                if ($j == 3) {
                    $arProgramacionRespaldo->setDia3($detalle[$j]);
                }
                if ($j == 4) {
                    $arProgramacionRespaldo->setDia4($detalle[$j]);
                }
                if ($j == 5) {
                    $arProgramacionRespaldo->setDia5($detalle[$j]);
                }
                if ($j == 6) {
                    $arProgramacionRespaldo->setDia6($detalle[$j]);
                }
                if ($j == 7) {
                    $arProgramacionRespaldo->setDia7($detalle[$j]);
                }
                if ($j == 8) {
                    $arProgramacionRespaldo->setDia8($detalle[$j]);
                }
                if ($j == 9) {
                    $arProgramacionRespaldo->setDia9($detalle[$j]);
                }
                if ($j == 10) {
                    $arProgramacionRespaldo->setDia10($detalle[$j]);
                }
                if ($j == 11) {
                    $arProgramacionRespaldo->setDia11($detalle[$j]);
                }
                if ($j == 12) {
                    $arProgramacionRespaldo->setDia12($detalle[$j]);
                }
                if ($j == 13) {
                    $arProgramacionRespaldo->setDia13($detalle[$j]);
                }
                if ($j == 14) {
                    $arProgramacionRespaldo->setDia14($detalle[$j]);
                }
                if ($j == 15) {
                    $arProgramacionRespaldo->setDia15($detalle[$j]);
                }
                if ($j == 16) {
                    $arProgramacionRespaldo->setDia16($detalle[$j]);
                }
                if ($j == 17) {
                    $arProgramacionRespaldo->setDia17($detalle[$j]);
                }
                if ($j == 18) {
                    $arProgramacionRespaldo->setDia18($detalle[$j]);
                }
                if ($j == 19) {
                    $arProgramacionRespaldo->setDia19($detalle[$j]);
                }
                if ($j == 20) {
                    $arProgramacionRespaldo->setDia20($detalle[$j]);
                }
                if ($j == 21) {
                    $arProgramacionRespaldo->setDia21($detalle[$j]);
                }
                if ($j == 22) {
                    $arProgramacionRespaldo->setDia22($detalle[$j]);
                }
                if ($j == 23) {
                    $arProgramacionRespaldo->setDia23($detalle[$j]);
                }
                if ($j == 24) {
                    $arProgramacionRespaldo->setDia24($detalle[$j]);
                }
                if ($j == 25) {
                    $arProgramacionRespaldo->setDia25($detalle[$j]);
                }
                if ($j == 26) {
                    $arProgramacionRespaldo->setDia26($detalle[$j]);
                }
                if ($j == 27) {
                    $arProgramacionRespaldo->setDia27($detalle[$j]);
                }
                if ($j == 28) {
                    $arProgramacionRespaldo->setDia28($detalle[$j]);
                }
                if ($j == 29) {
                    $arProgramacionRespaldo->setDia29($detalle[$j]);
                }
                if ($j == 30) {
                    $arProgramacionRespaldo->setDia30($detalle[$j]);
                }
                if ($j == 31) {
                    $arProgramacionRespaldo->setDia31($detalle[$j]);
                }
            }
            $em->persist($arProgramacionRespaldo);
        }
    }

}

