<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuNovedad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuNovedadRepository extends ServiceEntityRepository
{

    /**
     * @return string
     */
    public function getRuta(){
        return 'recursohumano_movimiento_credito_credito_';
    }

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuNovedad::class);
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

    public function validarFecha($fechaDesde, $fechaHasta, $codigoEmpleado)
    {
        $em = $this->getEntityManager();
        $boolValidar = TRUE;
        $strFechaDesde = $fechaDesde->format('Y-m-d');
        $strFechaHasta = $fechaHasta->format('Y-m-d');
        $qb = $this->getEntityManager()->createQueryBuilder()->from(RhuNovedad::class, 'n')
            ->select("count(n.codigoNovedadPk) AS novedades")
            ->where("n.fechaDesde BETWEEN '{$strFechaDesde}' AND '{$strFechaHasta}'")
            ->orWhere("n.fechaHasta BETWEEN '{$strFechaDesde}' AND '{$strFechaHasta}'")
            ->orWhere("n.fechaDesde >= '{$strFechaDesde}' AND n.fechaDesde <= '{$strFechaHasta}'")
            ->orWhere("n.fechaHasta >= '{$strFechaHasta}' AND n.fechaDesde <= '{$strFechaDesde}'")
            ->andWhere("n.codigoEmpleadoFk = '{$codigoEmpleado}'");
        $r = $qb->getQuery();
        $arrNovedades = $r->getResult();
        if ($arrNovedades[0]['novedades'] > 0) {
            $boolValidar = FALSE;
        }

        return $boolValidar;
    }


}