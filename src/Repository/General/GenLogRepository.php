<?php

namespace App\Repository\General;

use App\Entity\General\GenLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class GenLogRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
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
        $arGenLog=$em->createQueryBuilder()
            ->from('App:General\GenLog','genLog')
            ->select('genLog.camposSeguimiento')
            ->addSelect('genLog.fecha')
            ->addSelect('genLog.accion')
            ->where("genLog.codigoRegistroPk='{$codigoRegistro}'")
            ->andWhere("genLog.nombreEntidad='{$entidad}'")
            ->getQuery()->getResult();

        return $arGenLog;
    }


}