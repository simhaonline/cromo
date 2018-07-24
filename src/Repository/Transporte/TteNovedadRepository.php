<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteNovedad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TteNovedadRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteNovedad::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteNovedad::class, 'n')
            ->select('n.codigoNovedadPk')
            ->join('n.novedadTipoRel', 'nt')
            ->join('n.guiaRel', 'g')
            ->addSelect('nt.nombre')
            ->addSelect('g.numero')
            ->addSelect('n.descripcion')
            ->addSelect('n.solucion')
            ->addSelect('n.fechaReporte')
            ->addSelect('n.fechaAtencion')
            ->addSelect('n.fechaSolucion')
            ->where('n.codigoNovedadPk IS NOT NULL')
            ->orderBy('n.codigoNovedadPk', 'ASC');
        if ($session->get('filtroNumeroGuia') != '') {
            $queryBuilder->andWhere("g.numero LIKE '%{$session->get('filtroNumeroGuia')}%' ");
        }
        if ($session->get('filtroTteCodigoNovedadTipo')) {
            $queryBuilder->andWhere("n.codigoNovedadTipoFk = '{$session->get('filtroTteCodigoNovedadTipo')}'");
        }

        return $queryBuilder;
    }

    public function guia($codigoGuia): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT n.codigoNovedadPk,
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

    public function pendienteAtender(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT n.codigoNovedadPk,
                  n.fecha,
                  n.fechaReporte,
                  n.fechaSolucion,
                  n.descripcion,
                  n.descripcion,
                  n.estadoAtendido,
                  n.estadoReporte,
                  n.estadoSolucion,
                  nt.nombre as nombreTipo
        FROM App\Entity\Transporte\TteNovedad n 
        LEFT JOIN n.novedadTipoRel nt
        WHERE n.estadoAtendido = 0'
        );
        return $query->execute();
    }

    public function pendienteSolucionar(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT n.codigoNovedadPk,
                  n.fecha,
                  n.fechaReporte,
                  n.fechaSolucion,
                  n.descripcion,
                  n.estadoAtendido,
                  n.estadoReporte,
                  n.estadoSolucion,
                  nt.nombre as nombreTipo
        FROM App\Entity\Transporte\TteNovedad n 
        LEFT JOIN n.novedadTipoRel nt
        WHERE n.estadoAtendido = 1 AND n.estadoSolucion = 0'
        );
        return $query->execute();
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

}