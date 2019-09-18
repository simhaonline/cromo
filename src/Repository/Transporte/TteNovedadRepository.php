<?php

namespace App\Repository\Transporte;

use App\Controller\Estructura\FuncionesController;
use App\Entity\Transporte\TteNovedad;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TteNovedadRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteNovedad::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;
        $codigoNovedadTipoFK = null;
        $codigoClienteFk = null;
        $guiaNumero = null;
        $fechaReporteDesde = null;
        $fechaReporteHasta = null;
        if ($filtros) {
            $codigoNovedadTipoFK = $filtros['codigoNovedadTipoFK'] ?? null;
            $codigoClienteFk = $filtros['codigoClienteFk'] ?? null;
            $guiaNumero = $filtros['guiaNumero'] ?? null;
            $fechaReporteDesde = $filtros['fechaReporteDesde'] ?? null;
            $fechaReporteHasta = $filtros['fechaReporteHasta'] ?? null;
        }


        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteNovedad::class, 'n')
            ->select('n.codigoNovedadPk')
            ->join('n.novedadTipoRel', 'nt')
            ->join('n.guiaRel', 'g')
            ->join('g.clienteRel', 'c')
            ->addSelect('nt.nombre')
            ->addSelect('c.nombreCorto')
            ->addSelect('g.numero')
            ->addSelect('n.descripcion')
            ->addSelect('n.solucion')
            ->addSelect('n.fechaReporte')
            ->addSelect('n.fechaAtencion')
            ->addSelect('n.fechaSolucion')
            ->addSelect('c.codigoClientePk')
            ->where('n.codigoNovedadPk IS NOT NULL')
            ->orderBy('n.codigoNovedadPk', 'DESC');
        if ($guiaNumero) {
            $queryBuilder->andWhere("g.numero LIKE '%{$guiaNumero}%' ");
        }
        if ($codigoNovedadTipoFK) {
            $queryBuilder->andWhere("nt.codigoNovedadTipoPk = '{$codigoNovedadTipoFK}'");
        }
        if ($codigoClienteFk) {
            $queryBuilder->andWhere("g.codigoClienteFk = '{$codigoClienteFk}'");
        }

        if ($fechaReporteDesde) {
            $queryBuilder->andWhere("n.fechaReporte >= '{$fechaReporteDesde} 00:00:00'");
        }

        if ($fechaReporteHasta) {
            $queryBuilder->andWhere("n.fechaReporte <= '{$fechaReporteHasta} 23:59:59'");
        }
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder;
    }

    public function guia($codigoGuia): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT
                  n.codigoNovedadPk,
                  n.fecha,
                  n.fechaReporte,
                  n.fechaSolucion,
                  n.descripcion,
                  n.solucion,
                  n.estadoAtendido,
                  n.estadoReporte,
                  n.estadoSolucion,
                  nt.nombre as nombreTipo
        FROM App\Entity\Transporte\TteNovedad n 
        LEFT JOIN n.novedadTipoRel nt
        WHERE n.codigoGuiaFk = :codigoGuia'
        )->setParameter('codigoGuia', $codigoGuia);

        return $query->execute();
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function pendienteAtender()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteNovedad::class, 'n')
            ->select('n.codigoNovedadPk')
            ->join('n.novedadTipoRel', 'nt')
            ->join('n.guiaRel', 'g')
            ->join('g.clienteRel', 'c')
            ->addSelect('nt.nombre as nombreTipo')
            ->addSelect('n.codigoGuiaFk')
            ->addSelect('n.descripcion')
            ->addSelect('n.solucion')
            ->addSelect('n.fecha')
            ->addSelect('c.nombreCorto as cliente')
            ->addSelect('n.fechaReporte')
            ->addSelect('n.fechaAtencion')
            ->addSelect('n.fechaSolucion')
            ->addSelect('n.estadoAtendido')
            ->addSelect('n.estadoReporte')
            ->addSelect('n.estadoSolucion')
            ->where('n.estadoAtendido = 0');

        return $queryBuilder;
    }

    public function pendienteSolucionar()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteNovedad::class, 'n')
            ->select('n.codigoNovedadPk')
            ->addSelect('n.fecha')
            ->addSelect('n.fechaReporte')
            ->addSelect('n.fechaSolucion')
            ->addSelect('nt.nombre as tipo')
            ->addSelect('t.nombreCorto AS cliente')
            ->addSelect('n.codigoGuiaFk')
            ->addSelect('g.numero as numero')
            ->addSelect('g.documentoCliente')
            ->addSelect('n.descripcion')
            ->addSelect('g.codigoCumplidoFk')
            ->addSelect('n.estadoAtendido')
            ->addSelect('n.estadoReporte')
            ->addSelect('n.estadoSolucion')
            ->leftJoin('n.novedadTipoRel', 'nt')
            ->leftJoin('n.guiaRel', 'g')
            ->leftJoin('g.clienteRel', 't')
            ->where('n.estadoSolucion = 0')
        ->orderBy('n.codigoNovedadPk' , 'DESC');
        if ($session->get('filtroTteCodigoCliente')) {
            $queryBuilder->andWhere("g.codigoClienteFk = {$session->get('filtroTteCodigoCliente')}");
        }
        if ($session->get('filtroTtePendienteSolucionarFechaDesde') != null) {
            $queryBuilder->andWhere("n.fecha >= '{$session->get('filtroTtePendienteSolucionarFechaDesde')} 00:00:00'");
        }
        if ($session->get('filtroTtePendienteSolucionarFechaHasta') != null) {
            $queryBuilder->andWhere("n.fecha <= '{$session->get('filtroTtePendienteSolucionarFechaHasta')} 23:59:59'");
        }
        return $queryBuilder;
    }

    public function pendienteSolucionarCliente(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT COUNT(n.codigoNovedadPk) AS cantidad,
                g.codigoClienteFk,
                c.nombreCorto as cliente
        FROM App\Entity\Transporte\TteNovedad n 
        LEFT JOIN n.guiaRel g
        LEFT JOIN g.clienteRel c
        WHERE n.estadoSolucion = 0 
        GROUP BY g.codigoClienteFk');
        return $query->execute();
    }

    public function setAtender($arrNovedades, $arrControles): bool
    {
        $em = $this->getEntityManager();
        if ($arrNovedades) {
            if (count($arrNovedades) > 0) {
                foreach ($arrNovedades AS $codigo) {
                    $ar = $em->getRepository(TteNovedad::class)->find($codigo);
                    if($ar->getEstadoAtendido() == 0) {
                        $ar->setFechaAtencion(new \DateTime('now'));
                        $ar->setEstadoAtendido(1);
                    }
                    $em->persist($ar);
                }
                $em->flush();
            }
        }
        return true;
    }

    public function setReportar($arrNovedades, $arrControles): bool
    {
        $em = $this->getEntityManager();
        if ($arrNovedades) {
            if (count($arrNovedades) > 0) {
                foreach ($arrNovedades AS $codigo) {
                    $ar = $em->getRepository(TteNovedad::class)->find($codigo);
                    if($ar->getEstadoReporte() == 0) {
                        $ar->setFechaReporte(new \DateTime('now'));
                        $ar->setEstadoReporte(true);
                    }
                    $em->persist($ar);
                }
                $em->flush();
            }
        }
        return true;
    }

    public function reporteNovedad(){

        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteNovedad::class, 'n')
            ->select('n.codigoNovedadPk')
            ->join('n.novedadTipoRel', 'nt')
            ->join('n.guiaRel', 'g')
            ->join('g.clienteRel', 'c')
            ->join('g.ciudadDestinoRel', 'cd')
            ->addSelect('g.codigoGuiaPk AS guia')
            ->addSelect('c.nombreCorto AS cliente')
            ->addSelect('g.documentoCliente')
            ->addSelect('g.remitente')
            ->addSelect('cd.nombre AS ciudadDestino')
            ->addSelect('g.unidades')
            ->addSelect('g.pesoVolumen')
            ->addSelect('nt.nombre AS nombreTipo')
            ->addSelect('n.descripcion')
            ->addSelect('n.fechaRegistro')
            ->addSelect('g.fechaIngreso')
            ->addSelect('n.estadoAtendido')
            ->addSelect('n.estadoSolucion')
            ->addSelect('g.empaqueReferencia')
            ->where('n.codigoNovedadPk IS NOT NULL')
            ->orderBy('n.codigoNovedadPk', 'DESC');
        $fecha =  new \DateTime('now');
        if ($session->get('filtroFechaDesde') != null) {
            $queryBuilder->andWhere("n.fechaRegistro >= '{$session->get('filtroFechaDesde')} 00:00:00'");
        } else {
            $queryBuilder->andWhere("n.fechaRegistro >='" . $fecha->format('Y-m-d') . " 00:00:00'");
        }
        if ($session->get('filtroFechaHasta') != null) {
            $queryBuilder->andWhere("n.fechaRegistro <= '{$session->get('filtroFechaHasta')} 23:59:59'");
        } else {
            $queryBuilder->andWhere("n.fechaRegistroo <= '" . $fecha->format('Y-m-d') . " 23:59:59'");
        }
        if($session->get('filtroTteCodigoCliente')){
            $queryBuilder->andWhere("g.codigoClienteFk = {$session->get('filtroTteCodigoCliente')}");
        }
        switch ($session->get('filtroTteNovedadEstadoAtendido')) {
            case '0':
                $queryBuilder->andWhere("n.estadoAtendido = 0");
                break;
            case '1':
                $queryBuilder->andWhere("n.estadoAtendido = 1");
                break;
        }
        switch ($session->get('filtroTteNovedadEstadoSolucionado')) {
            case '0':
                $queryBuilder->andWhere("n.estadoSolucion = 0");
                break;
            case '1':
                $queryBuilder->andWhere("n.estadoSolucion = 1");
                break;
        }
        return $queryBuilder;

    }

    public function utilidadNotificar($codigoCliente)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteNovedad::class, 'n');
        $queryBuilder
            ->select('n.codigoNovedadPk')
            ->addSelect('n.codigoGuiaFk')
            ->addSelect('g.fechaIngreso')
            ->addSelect('g.empaqueReferencia')
            ->addSelect('g.documentoCliente')
            ->addSelect('g.remitente')
            ->addSelect('nt.nombre AS causal')
            ->addSelect('n.descripcion')
            ->addSelect('g.pesoReal')
            ->addSelect('g.unidades')
            ->leftJoin('n.guiaRel', 'g')
            ->leftJoin('n.novedadTipoRel', 'nt')
            ->where('g.codigoClienteFk = ' . $codigoCliente)
            ->andWhere('n.estadoSolucion = 0');
        return $queryBuilder->getQuery()->getResult();
    }

    public function eliminar($arrSeleccionados)
    {
        foreach ($arrSeleccionados as $arrSeleccionado) {
            $arNovedad = $this->getEntityManager()->getRepository(TteNovedad::class)->find($arrSeleccionado);
            if($arNovedad) {
                if ($arNovedad->getEstadoSolucion() == 0) {
                    $this->getEntityManager()->remove($arNovedad);
                }else {
                    Mensajes::error("No se puede eliminar el registro ya se encuentra solucionado");
                }
            }
        }
        $this->getEntityManager()->flush();
    }

    public function despacho($codigoDespacho){
        $qb = $this->getEntityManager()->createQueryBuilder()->from(TteNovedad::class,'n')
            ->select('n.codigoNovedadPk')
            ->addSelect('n.fecha')
            ->addSelect('n.fechaReporte')
            ->addSelect('n.fechaSolucion')
            ->addSelect('n.descripcion')
            ->addSelect('n.solucion')
            ->addSelect('n.estadoAtendido')
            ->addSelect('n.estadoReporte')
            ->addSelect('n.estadoSolucion')
            ->addSelect('nt.nombre as nombreTipo')
            ->where("n.codigoDespachoFk = {$codigoDespacho}")
            ->leftJoin('n.novedadTipoRel', 'nt');
        return $qb->getQuery()->execute();
    }

    /**
     * @return mixed
     * @throws \Doctrine\DBAL\DBALException
     */
    public function novedadesPorDiaMesAtual(){
        $fecha = new \DateTime('now');
        $fechaDesde = $fecha->format('Y-m').'-01';
        //Obtenemos el ultimo dia del mes seleccionado
        $fechaHasta = FuncionesController::ultimoDia($fecha);
        $sql = "SELECT DAY(fecha_registro) as dia, 
                COUNT(codigo_novedad_pk) as cantidad
                FROM tte_novedad 
                WHERE fecha_registro >= '" . $fechaDesde . " 00:00:00' AND fecha_registro <='" . $fechaHasta ." 23:59:59'  
                GROUP BY DAY(fecha_registro)";
        $connection = $this->getEntityManager()->getConnection();
        $statement = $connection->prepare($sql);
        $statement->execute();
        $results = $statement->fetchAll();
        return $results;
    }

    /**
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function novedadesPorMesAnioActual(){
        $fecha = new \DateTime('now');
        $fechaDesde = $fecha->format('Y') . "-01-01";
        $fechaHasta = $fecha->format('Y') . "-12-31";
        $sql = "SELECT MONTH(fecha_registro) as mes, 
                COUNT(codigo_novedad_pk) as cantidad
                FROM tte_novedad 
                WHERE fecha_registro >= '" . $fechaDesde . " 00:00:00' AND fecha_registro <='" . $fechaHasta ." 23:59:59'  
                GROUP BY MONTH(fecha_registro)";
        $connection = $this->getEntityManager()->getConnection();
        $statement = $connection->prepare($sql);
        $statement->execute();
        $results = $statement->fetchAll();
        return $results;
    }


    /**
     * @param $codigoGuia
     * @return mixed
     */
    public function apiConsulta($codigoGuia){
        $qb = $this->_em->createQueryBuilder()->from(TteNovedad::class,'n')
            ->select('n.estadoSolucion')
            ->addSelect('n.solucion')
            ->addSelect('n.fechaSolucion')
            ->addSelect('n.codigoNovedadTipoFk')
            ->addSelect('n.descripcion')
            ->where('n.codigoGuiaFk = '.$codigoGuia);
        return $qb->getQuery()->getResult();
    }

    public function fechaGuia($raw)
    {
        $filtros = $raw['filtros'];
        $fechaDesde = $filtros['fechaDesde']??null;
        $fechaHasta = $filtros['fechaHasta']??null;
        $codigoCliente = $filtros['codigoCliente']??null;
        if($fechaDesde && $fechaHasta) {
            $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteNovedad::class, 'n')
                ->select('n.codigoNovedadPk')
                ->addSelect('n.codigoGuiaFk')
                ->addSelect('n.descripcion')
                ->addSelect('n.solucion')
                ->addSelect('n.fecha')
                ->addSelect('n.fechaReporte')
                ->addSelect('n.fechaAtencion')
                ->addSelect('n.fechaSolucion')
                ->addSelect('nt.nombre as novedadTipoNombre')
                ->addSelect('g.numero')
                ->join('n.novedadTipoRel', 'nt')
                ->join('n.guiaRel', 'g')
                ->where('n.estadoSolucion = 0')
                ->andWhere("g.fechaIngreso >='{$fechaDesde} 00:00:00'")
                ->andWhere("g.fechaIngreso <='{$fechaHasta} 23:59:59'")
                ->orderBy('n.codigoNovedadPk', 'DESC');
            if($codigoCliente) {
                $queryBuilder->andWhere("g.codigoClienteFk = {$codigoCliente}");
            }
            return $queryBuilder->getQuery()->getResult();
        } else {
            return [];
        }
    }


}