<?php

namespace App\Repository\General;

use App\Entity\General\GenEvento;
use App\Entity\General\GenTarea;
use App\Entity\General\GenTareaPrioridad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class GenTareaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GenTarea::class);
    }

    public function lista($usuario){
        return $this->_em->createQueryBuilder()->from(GenTarea::class,'t')
            ->select('t.codigoTareaPk')
            ->addSelect('t.titulo')
            ->addSelect('tp.nombre as nombrePrioridad')
            ->addSelect('tp.icono')
            ->addSelect('t.descripcion')
            ->addSelect('t.estadoTerminado')
            ->addSelect('t.fecha')
            ->addSelect('t.codigoTareaPrioridadFk')
            ->addSelect('t.usuarioAsigna')
            ->leftJoin('t.tareaPrioridadRel','tp')
            ->where("t.usuarioRecibe = '{$usuario}'")
            ->orderBy('t.codigoTareaPrioridadFk','ASC')
            ->getQuery()->execute();
    }

    public function tareasRecibidas($usuario){
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(GenTarea::class, 't')
            ->select('t.codigoTareaPk')
            ->addSelect('t.titulo')
            ->addSelect('tp.nombre as nombrePrioridad')
            ->addSelect('tp.icono')
            ->addSelect('t.descripcion')
            ->addSelect('t.estadoTerminado')
            ->addSelect('t.fecha')
            ->addSelect('t.codigoTareaPrioridadFk')
            ->addSelect('t.usuarioAsigna')
            ->leftJoin('t.tareaPrioridadRel','tp')
            ->where("t.usuarioRecibe = '{$usuario}'")
            ->orderBy('t.codigoTareaPrioridadFk','ASC');
        if ($session->get('filtroGenTareaRecibidaFechaDesde') != null) {
            $queryBuilder->andWhere("t.fecha >= '{$session->get('filtroGenTareaRecibidaFechaDesde')} 00:00:00'");
        }
        if ($session->get('filtroGenTareaRecibidaFechaHasta') != null) {
            $queryBuilder->andWhere("t.fecha <= '{$session->get('filtroGenTareaRecibidaFechaHasta')} 23:59:59'");
        }
        if ($session->get('filtroGenTareaPrioridad') != '') {
            $queryBuilder->andWhere("t.codigoTareaPrioridadFk =  '{$session->get('filtroGenTareaPrioridad')}'");
        }
        switch ($session->get('filtroGenTareaRecibida')) {
            case '0':
                $queryBuilder->andWhere("t.estadoTerminado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("t.estadoTerminado = 1");
                break;
        }
        return $queryBuilder;
    }

    public function tareasAsignadas($usuario){
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(GenTarea::class, 't')
            ->select('t.codigoTareaPk')
            ->addSelect('t.titulo')
            ->addSelect('tp.nombre as nombrePrioridad')
            ->addSelect('tp.icono')
            ->addSelect('t.descripcion')
            ->addSelect('t.estadoTerminado')
            ->addSelect('t.fecha')
            ->addSelect('t.codigoTareaPrioridadFk')
            ->addSelect('t.usuarioRecibe')
            ->leftJoin('t.tareaPrioridadRel','tp')
            ->where("t.usuarioAsigna = '{$usuario}'")
            ->orderBy('t.codigoTareaPrioridadFk','ASC');
        if ($session->get('filtroGenTareaAsiganadaFechaDesde') != null) {
            $queryBuilder->andWhere("t.fecha >= '{$session->get('filtroGenTareaAsiganadaFechaDesde')} 00:00:00'");
        }
        if ($session->get('filtroGenTareaAsiganadaFechaHasta') != null) {
            $queryBuilder->andWhere("t.fecha <= '{$session->get('filtroGenTareaAsiganadaFechaHasta')} 23:59:59'");
        }
        if ($session->get('filtroGenTareaPrioridad') != '') {
            $queryBuilder->andWhere("t.codigoTareaPrioridadFk =  '{$session->get('filtroGenTareaPrioridad')}'");
        }
        if ($session->get('filtroUsuario') != '') {
            $queryBuilder->andWhere("t.usuarioRecibe =  '{$session->get('filtroUsuario')}'");
        }
        switch ($session->get('filtroGenTareaAsignada')) {
            case '0':
                $queryBuilder->andWhere("t.estadoTerminado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("t.estadoTerminado = 1");
                break;
        }
        return $queryBuilder;
    }
}
