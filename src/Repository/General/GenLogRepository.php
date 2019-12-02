<?php

namespace App\Repository\General;

use App\Entity\General\GenLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class GenLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GenLog::class);
    }

    public function lista(){
        $session= new Session();
        $queryBuilder=$this->getEntityManager()->createQueryBuilder()->from('App:General\GenLog','gl')
            ->select('gl.codigoLogPk')
            ->addSelect('gl.codigoRegistroPk')
            ->addSelect('gl.nombreEntidad')
            ->addSelect('gl.fecha')
            ->addSelect('gl.nombreUsuario')
            ->addSelect('gl.accion')
            ->addSelect('gl.ruta')
            ->addSelect('gl.camposSeguimiento')
            ->orderBy('gl.fecha','DESC');

        if ($session->get('filtroGenLogCodigoRegistro') != '') {
            $queryBuilder->andWhere("gl.codigoRegistroPk = '{$session->get('filtroGenLogCodigoRegistro')}' ");
        }
        if($session->get('filtroGenLogFechaDesde')||$session->get('filtroGenLogFechaHasta')){
            $queryBuilder->andWhere("gl.fecha >='{$session->get('filtroGenLogFechaDesde')}'")
            ->andWhere("gl.fecha <='{$session->get('filtroGenLogFechaHasta')}'");
        }
        if($session->get('filtroGenLogAccion')){
            $queryBuilder->andWhere("gl.accion='{$session->get('filtroGenLogAccion')}'");
        }
        if($session->get('filtroGenLogModelo')){
            $queryBuilder->andWhere("gl.nombreEntidad='{$session->get('filtroGenLogModelo')}'");
        }
        $queryBuilder->setMaxResults(100);
        return $queryBuilder;
    }

    public function getCampoSeguimiento($codigoRegistro, $entidad){
        $em=$this->getEntityManager();
        $arLog=$em->createQueryBuilder()
            ->from('App:General\GenLog','l')
            ->select('l.camposSeguimiento')
            ->addSelect('l.fecha')
            ->addSelect('l.accion')
            ->addSelect('l.codigoUsuarioFk')
            ->where("l.codigoRegistroPk='{$codigoRegistro}'")
            ->andWhere("l.nombreEntidad='{$entidad}'")
            ->getQuery()->getResult();

        return $arLog;
    }


}