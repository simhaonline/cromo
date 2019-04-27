<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuVacacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuVacacionRepository extends ServiceEntityRepository
{

    /**
     * @return string
     */
    public function getRuta(){
        return 'recursohumano_movimiento_nomina_vacacion_';
    }

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuVacacion::class);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function lista()
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuCredito::class, 'e');
        $queryBuilder
            ->select('e.codigoCreditoPk');
        return $queryBuilder;
    }

    public function diasProgramacion($codigoEmpleado, $codigoContrato, $fechaDesde, $fechaHasta)
    {
        $em = $this->getEntityManager();
        $query = $em->createQueryBuilder()->from(RhuVacacion::class, 'v')
            ->select('v.codigoVacacionPk')
            ->addSelect('v.fechaDesdeDisfrute')
            ->addSelect('v.fechaHastaDisfrute')
            ->andWhere("(((v.fechaDesdeDisfrute BETWEEN '$fechaDesde' AND '$fechaHasta') OR (v.fechaHastaDisfrute BETWEEN '$fechaDesde' AND '$fechaHasta')) "
                . "OR (v.fechaDesdeDisfrute >= '$fechaDesde' AND v.fechaDesdeDisfrute <= '$fechaHasta') "
                . "OR (v.fechaHastaDisfrute >= '$fechaHasta' AND v.fechaDesdeDisfrute <= '$fechaDesde')) "
                . "AND v.codigoEmpleadoFk = '" . $codigoEmpleado . "' AND v.diasDisfrutados > 0 AND v.estadoAnulado = 0");
        if($codigoContrato) {
            $query->andWhere("v.codigoContratoFk = " . $codigoContrato);
        }
        $arVacaciones = $query->getQuery()->getResult();
        $intDiasDevolver = 0;
        $vrIbc = 0;
        foreach ($arVacaciones as $arVacacion) {
            $intDias = 0;
            $dateFechaDesde = date_create($fechaDesde);
            $dateFechaHasta = date_create($fechaHasta);
            if ($arVacacion['fechaDesdeDisfrute'] < $dateFechaDesde) {
                $dateFechaDesde = $dateFechaDesde;
            } else {
                $dateFechaDesde = $arVacacion['fechaDesdeDisfrute'];
            }

            if ($arVacacion['fechaHastaDisfrute'] > $dateFechaHasta) {
                $dateFechaHasta = $dateFechaHasta;
            } else {
                $dateFechaHasta = $arVacacion['fechaHastaDisfrute'];
            }
            if ($dateFechaDesde != "" && $dateFechaHasta != "") {
                $intDias = $dateFechaDesde->diff($dateFechaHasta);
                $intDias = $intDias->format('%a');
                $intDias = $intDias + 1;
                $intDiasDevolver += $intDias;
            }
            $vrIbc += 0;
            //$vrIbc += $intDias * $arVacacion->getVrIbcPromedio();
        }
        $arrDevolver = array('dias' => $intDiasDevolver, 'ibc' => $vrIbc);
        return $arrDevolver;

    }


}