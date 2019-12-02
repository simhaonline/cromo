<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuNovedad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuNovedadRepository extends ServiceEntityRepository
{

    /**
     * @return string
     */
    public function getRuta(){
        return 'recursohumano_movimiento_credito_credito_';
    }

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuNovedad::class);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuNovedad::class, 'n')
            ->select('n.codigoNovedadPk')
            ->addSelect('nt.nombre as novedadTipo ')
            ->addSelect('n.fecha')
            ->addSelect('n.codigoEmpleadoFk')
            ->addSelect('e.nombreCorto as empleado ')
            ->addSelect('e.numeroIdentificacion as numeroIdentificacion ')
            ->addSelect('n.codigoContratoFk')
            ->addSelect('n.fechaDesde')
            ->addSelect('n.fechaHasta')
            ->leftJoin('n.novedadTipoRel', 'nt')
            ->leftJoin('n.empleadoRel', 'e');

        if ($session->get('RhuNovedad_codigoNovedadPk')) {
            $queryBuilder->andWhere("n.codigoNovedadPk = '{$session->get('RhuNovedad_codigoNovedadPk')}'");
        }

        if ($session->get('RhuNovedad_codigoNovedadTipoFk')) {
            $queryBuilder->andWhere("n.codigoNovedadTipoFk = '{$session->get('RhuNovedad_codigoNovedadTipoFk')}'");
        }

        if ($session->get('RhuNovedad_codigoEmpleadoFk')) {
            $queryBuilder->andWhere("n.codigoEmpleadoFk = '{$session->get('RhuNovedad_codigoEmpleadoFk')}'");
        }

        if ($session->get('RhuNovedad_fechaDesde') != null) {
            $queryBuilder->andWhere("doc.fechaDesde >= '{$session->get('RhuNovedad_fechaDesde')} 00:00:00'");
        }

        if ($session->get('RhuNovedad_fechaHasta') != null) {
            $queryBuilder->andWhere("doc.fechaHasta <= '{$session->get('RhuNovedad_fechaHasta')} 23:59:59'");
        }

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