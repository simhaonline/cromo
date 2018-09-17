<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteRecogida;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TteRecogidaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteRecogida::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteRecogida::class, 'r');
        $queryBuilder
            ->select('r.codigoRecogidaPk')
            ->addSelect('r.fechaRegistro')
            ->addSelect('r.fecha')
            ->addSelect('c.nombreCorto AS clienteNombreCorto')
            ->addSelect('co.nombre AS ciudad')
            ->addSelect('r.estadoProgramado')
            ->addSelect('r.estadoRecogido')
            ->addSelect('r.unidades')
            ->addSelect('r.pesoReal')
            ->addSelect('r.pesoVolumen')
            ->addSelect('r.anunciante')
            ->addSelect('r.direccion')
            ->addSelect('r.telefono')
            ->addSelect('r.estadoAutorizado')
            ->addSelect('r.codigoOperacionFk')
            ->leftJoin('r.clienteRel', 'c')
            ->leftJoin('r.ciudadRel', 'co');
        if($session->get('filtroTteCodigoCliente')){
            $queryBuilder->andWhere("r.codigoClienteFk = {$session->get('filtroTteCodigoCliente')}");
        }
        if($session->get('filtroTteRecogidaCodigo') != ''){
            $queryBuilder->andWhere("r.codigoRecogidaPk = {$session->get('filtroTteRecogidaCodigo')}");
        }
        switch ($session->get('filtroTteRecogidaEstadoProgramado')) {
            case '0':
                $queryBuilder->andWhere("r.estadoProgramado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("r.estadoProgramado = 1");
                break;
        }
        $queryBuilder->orderBy('r.fecha', 'DESC');

        return $queryBuilder;

    }

    public function pendienteProgramar()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteRecogida::class, 'r');
        $queryBuilder
            ->select('r.codigoRecogidaPk')
            ->addSelect('r.fechaRegistro')
            ->addSelect('r.fecha')
            ->addSelect('c.nombreCorto AS clienteNombreCorto')
            ->addSelect('co.nombre AS ciudad')
            ->addSelect('r.anunciante')
            ->addSelect('r.direccion')
            ->addSelect('r.telefono')
            ->addSelect('r.unidades')
            ->addSelect('r.pesoReal')
            ->addSelect('r.pesoVolumen')
            ->leftJoin('r.clienteRel', 'c')
            ->leftJoin('r.ciudadRel', 'co')
            ->where('r.estadoProgramado = 0')
            ->andWhere('r.estadoAnulado = 0');
        if($session->get('filtroTteCodigoCliente')){
            $queryBuilder->andWhere("r.codigoClienteFk = {$session->get('filtroTteCodigoCliente')}");
        }
        if($session->get('filtroTteRecogidaCodigo') != ''){
            $queryBuilder->andWhere("r.codigoRecogidaPk = {$session->get('filtroTteRecogidaCodigo')}");
        }
        switch ($session->get('filtroTteRecogidaEstadoProgramado')) {
            case '0':
                $queryBuilder->andWhere("r.estadoProgramado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("r.estadoProgramado = 1");
                break;
        }
        $queryBuilder->orderBy('r.fecha', 'DESC');

        return $queryBuilder;

    }

    public function autorizar($arRecogida)
    {
        $arRecogida->setEstadoAutorizado(1);
        $this->getEntityManager()->persist($arRecogida);
        $this->getEntityManager()->flush();
    }

    public function desAutorizar($arRecogida)
    {
        if ($arRecogida->getEstadoAutorizado() == 1 && $arRecogida->getEstadoAprobado() == 0) {
            $arRecogida->setEstadoAutorizado(0);
            $this->getEntityManager()->persist($arRecogida);
            $this->getEntityManager()->flush();
        } else {
            Mensajes::error('No se puede desautorizar, el registro ya se encuentra aprobado');
        }
    }

    public function aprobar($arRecogida)
    {
        $arRecogida->setEstadoAprobado(1);
        $this->getEntityManager()->persist($arRecogida);
        $this->getEntityManager()->flush();
    }

    public function despacho($codigoRecogidaDespacho)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteRecogida::class, 'r');
        $queryBuilder
            ->select('r.codigoRecogidaPk')
            ->addSelect('r.fechaRegistro')
            ->addSelect('r.fecha')
            ->addSelect('c.nombreCorto AS clienteNombreCorto')
            ->addSelect('co.nombre AS ciudad')
            ->addSelect('r.estadoProgramado')
            ->addSelect('r.estadoRecogido')
            ->addSelect('r.unidades')
            ->addSelect('r.pesoReal')
            ->addSelect('r.pesoVolumen')
            ->leftJoin('r.clienteRel', 'c')
            ->leftJoin('r.ciudadRel', 'co')
            ->where('r.codigoDespachoRecogidaFk = ' . $codigoRecogidaDespacho);

        return $queryBuilder->getQuery()->getResult();

    }

    public function despachoPendiente($fecha = null): array
    {
        $em = $this->getEntityManager();
        $fechaDesde = $fecha . " 00:00";
        $fechaHasta = $fecha . " 23:59";
        $query = $em->createQuery(
            'SELECT r.codigoRecogidaPk, c.nombreCorto AS clienteNombreCorto, co.nombre AS ciudad,
              r.fecha, r.estadoProgramado, r.estadoRecogido, r.unidades, r.pesoReal, r.pesoVolumen,
              r.codigoOperacionFk, r.anunciante, r.direccion, r.telefono
        FROM App\Entity\Transporte\TteRecogida r 
        LEFT JOIN r.clienteRel c
        LEFT JOIN r.ciudadRel co
        WHERE r.estadoProgramado = 0 AND r.fecha BETWEEN :fechaDesde AND :fechaHasta'
        )->setParameter('fechaDesde', $fechaDesde)
            ->setParameter('fechaHasta', $fechaHasta);
        return $query->execute();
    }

    public function listaRecoge($codigoRecogidaDespacho): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT r.codigoRecogidaPk, r.fechaRegistro, r.fecha, c.nombreCorto AS clienteNombreCorto, co.nombre AS ciudad, 
        r.estadoProgramado, r.estadoRecogido, r.unidades, r.pesoReal, r.pesoVolumen
        FROM App\Entity\Transporte\TteRecogida r 
        LEFT JOIN r.clienteRel c
        LEFT JOIN r.ciudadRel co        
        WHERE r.codigoDespachoRecogidaFk = :codigoDespachoRecogida AND r.estadoRecogido = 0'
        )->setParameter('codigoDespachoRecogida', $codigoRecogidaDespacho);

        return $query->execute();

    }

    public function listaDescarga($codigoRecogidaDespacho): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT r.codigoRecogidaPk, r.fechaRegistro, r.fecha, c.nombreCorto AS clienteNombreCorto, co.nombre AS ciudad, 
        r.estadoProgramado, r.estadoRecogido, r.unidades, r.pesoReal, r.pesoVolumen
        FROM App\Entity\Transporte\TteRecogida r 
        LEFT JOIN r.clienteRel c
        LEFT JOIN r.ciudadRel co        
        WHERE r.codigoDespachoRecogidaFk = :codigoDespachoRecogida AND r.estadoRecogido = 1 AND r.estadoDescargado = 0'
        )->setParameter('codigoDespachoRecogida', $codigoRecogidaDespacho);

        return $query->execute();

    }

    public function despachoSinDescargar($codigoRecogidaDespacho): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT r.codigoRecogidaPk, r.fechaRegistro, r.fecha, c.nombreCorto AS clienteNombreCorto, co.nombre AS ciudad, 
        cd.nombre AS ciudadDestino, r.estadoProgramado, r.estadoRecogido, r.unidades, r.pesoReal, r.pesoVolumen
        FROM App\Entity\Transporte\TteRecogida r 
        LEFT JOIN r.clienteRel c
        LEFT JOIN r.ciudadRel co
        LEFT JOIN r.ciudadDestinoRel cd
        WHERE r.estadoRecogido = 0 AND r.codigoDespachoRecogidaFk = :codigoDespachoRecogida'
        )->setParameter('codigoDespachoRecogida', $codigoRecogidaDespacho);

        return $query->execute();
    }

    public function cuentaPendientes($fechaDesde, $fechaHasta): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT COUNT(r.codigoRecogidaPk) as cantidad, SUM(r.unidades) as unidades, SUM(r.pesoReal) as pesoReal
        FROM App\Entity\Transporte\TteRecogida r
        WHERE r.estadoProgramado = 0 AND r.fecha >= :fechaDesde AND  r.fecha <= :fechaHasta')
            ->setParameter('fechaDesde', $fechaDesde . " 00:00")
            ->setParameter('fechaHasta', $fechaHasta . " 23:59");
        $arrRecogidas = $query->getSingleResult();
        return $arrRecogidas;
    }

    public function cuentaSinDescargar($fechaDesde, $fechaHasta): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT COUNT(r.codigoRecogidaPk) as cantidad, SUM(r.unidades) as unidades, SUM(r.pesoReal) as pesoReal
        FROM App\Entity\Transporte\TteRecogida r
        WHERE r.estadoProgramado = 1 AND r.estadoRecogido = 0 AND r.fecha >= :fechaDesde AND  r.fecha <= :fechaHasta')
            ->setParameter('fechaDesde', $fechaDesde . " 00:00")
            ->setParameter('fechaHasta', $fechaHasta . " 23:59");
        $arrRecogidas = $query->getSingleResult();
        return $arrRecogidas;
    }

    public function cuentaDescargadas($fechaDesde, $fechaHasta): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT COUNT(r.codigoRecogidaPk) as cantidad, SUM(r.unidades) as unidades, SUM(r.pesoReal) as pesoReal
        FROM App\Entity\Transporte\TteRecogida r
        WHERE r.estadoRecogido = 1 AND r.fecha >= :fechaDesde AND  r.fecha <= :fechaHasta')
            ->setParameter('fechaDesde', $fechaDesde . " 00:00")
            ->setParameter('fechaHasta', $fechaHasta . " 23:59");
        $arrRecogidas = $query->getSingleResult();
        return $arrRecogidas;
    }

    public function resumenCuenta($fechaDesde, $fechaHasta): array
    {
        $arrResumen = array();
        $pendientes = $this->cuentaPendientes($fechaDesde, $fechaHasta);
        $sinDescargar = $this->cuentaSinDescargar($fechaDesde, $fechaHasta);
        $descagadas = $this->cuentaDescargadas($fechaDesde, $fechaHasta);
        $arrResumen['pendientes'] = $pendientes;
        $arrResumen['sinDescargar'] = $sinDescargar;
        $arrResumen['descargadas'] = $descagadas;
        return $arrResumen;
    }

    public function resumenOperacion($fechaDesde, $fechaHasta): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT r.codigoOperacionFk, COUNT(r.codigoRecogidaPk) as cantidad, SUM(r.unidades) as unidades, SUM(r.pesoReal) as pesoReal
        FROM App\Entity\Transporte\TteRecogida r
        WHERE r.fecha >= :fechaDesde AND  r.fecha <= :fechaHasta GROUP BY r.codigoOperacionFk')
            ->setParameter('fechaDesde', $fechaDesde . " 00:00")
            ->setParameter('fechaHasta', $fechaHasta . " 23:59");
        $arrRecogidas = $query->getResult();
        return $arrRecogidas;
    }


    public function descarga($arrRecogidas, $arrControles): bool
    {
        $em = $this->getEntityManager();
        if($arrRecogidas) {
            if (count($arrRecogidas) > 0) {
                foreach ($arrRecogidas AS $codigo) {
                    $arRecogida = $em->getRepository(TteRecogida::class)->find($codigo);
                    $arRecogida->setUnidades($arrControles['txtUnidades'.$codigo]);
                    $arRecogida->setPesoReal($arrControles['txtPesoReal'.$codigo]);
                    $arRecogida->setPesoVolumen($arrControles['txtPesoVolumen'.$codigo]);
                    $arRecogida->setEstadoDescargado(1);
                    $em->persist($arRecogida);
                }
                $em->flush();
            }
        }
        return true;
    }

    public function recoge($arrRecogidas, $arrControles): bool
    {
        $em = $this->getEntityManager();
        if($arrRecogidas) {
            if (count($arrRecogidas) > 0) {
                foreach ($arrRecogidas AS $codigo) {
                    $arRecogida = $em->getRepository(TteRecogida::class)->find($codigo);
                    $fechaHora = date_create($arrControles['txtFecha'.$codigo] . " " . $arrControles['txtHora'.$codigo]);
                    $arRecogida->setFechaEfectiva($fechaHora);
                    $arRecogida->setEstadoRecogido(1);
                    $em->persist($arRecogida);
                }
                $em->flush();
            }
        }
        return true;
    }

    /**
     * @param $codigo
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function contarDetalles($codigo){
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteRecogida::class,'r')
            ->select('COUNT(r.codigoRecogidaPk)')
            ->where("r.codigoDespachoRecogidaFk = {$codigo}");
        $resultado = $queryBuilder->getQuery()->getSingleResult();
        return $resultado[1];
    }

    public function listaFormato($codigoDespachoRecogida)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteRecogida::class, 'r');
        $queryBuilder
            ->select('r.codigoRecogidaPk')
            ->addSelect('r.fecha')
            ->addSelect('c.nombreCorto AS clienteNombreCorto')
            ->addSelect('r.unidades')
            ->addSelect('r.pesoReal')
            ->leftJoin('r.clienteRel', 'c')
            ->where('r.codigoDespachoRecogidaFk = ' . $codigoDespachoRecogida);
        $queryBuilder->orderBy('r.fecha', 'ASC');

        return $queryBuilder->getQuery()->getResult() ;
    }

}