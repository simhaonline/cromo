<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuEmbargo;
use App\Entity\RecursoHumano\RhuLiquidacion;
use App\Entity\RecursoHumano\RhuReclamo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuLiquidacionRepository extends ServiceEntityRepository
{

    /**
     * @return string
     */
    public function getRuta(){
        return 'recursohumano_movimiento_reclamo_reclamo_';
    }

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuLiquidacion::class);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function lista()
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuReclamo::class, 'e');
        $queryBuilder
            ->select('e.codigoEmbargoPk');
        return $queryBuilder;
    }

    /**
     * @return array
     */
    public function parametrosLista(){
        $arEmbargo = new RhuEmbargo();
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuEmbargo::class,'re')
            ->select('re.codigoEmbargoPk')
            ->addSelect('re.fecha')
            ->where('re.codigoEmbargoPk <> 0');
        $arrOpciones = ['json' =>'[{"campo":"codigoEmbargoPk","ayuda":"Codigo del embargo","titulo":"ID"},
        {"campo":"fecha","ayuda":"Fecha de registro","titulo":"FECHA"}]',
            'query' => $queryBuilder,'ruta' => $this->getRuta()];
        return $arrOpciones;
    }

    /**
     * @return mixed
     */
    public function parametrosExcel(){
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuEmbargo::class,'re')
            ->select('re.codigoEmbargoPk')
            ->addSelect('re.fecha')
            ->where('re.codigoEmbargoPk <> 0');
        return $queryBuilder->getQuery()->execute();
    }

    public function diasPrestacionesHasta($intDias, $dateFechaDesde)
    {
        $strFechaHasta = "";
        $intAnio = $dateFechaDesde->format('Y');
        $intMes = $dateFechaDesde->format('n');
        $intDia = $dateFechaDesde->format('j');
        $intDiasAcumulados = 1;
        $i = $intDia;
        while ($intDiasAcumulados <= $intDias) {
            //echo $intDiasAcumulados . "(" . $i . ")" . "(" . $intMes . ")" . "(" . $intAnio . ")" . "<br />";
            $fechaHastaPeriodo = $intAnio . "-" . $intMes . "-" . $i;
            if ($i == 30 || $i == 31) {
                $i = 1;
                if ($intMes == 12) {
                    $intMes = 1;
                    $intAnio++;
                } else {
                    $intMes++;
                }
            } else {
                $i++;
            }
            $intDiasAcumulados++;
        }
        $fechaHastaPeriodo = date_create_from_format('Y-n-j H:i', $fechaHastaPeriodo . " 00:00");
        // validacion para los meses de 31 dias
        if ($intDia == 31 && $intDias == 361) {
            $fechaHastaPeriodo = date_create(date('Y-m-j', strtotime('+1 day', strtotime($fechaHastaPeriodo->format('Y-m-d')))));
        }
        return $fechaHastaPeriodo;
    }


}