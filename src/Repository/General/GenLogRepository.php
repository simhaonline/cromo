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
            ->addSelect('gl.codigoUsuarioFk')
            ->addSelect('gl.nombreUsuario')
            ->addSelect('gl.accion')
            ->addSelect('gl.ruta')
            ->addSelect('gl.fecha')
            ->addSelect('gl.nombreEntidad')
//            ->where('gl.codigoUsuarioFk IS NOT NULL')
            ->orderBy('gl.fecha','DESC');

        if ($session->get('filtroGenLogCodigoRegistro') != '') {
            $queryBuilder->andWhere("gl.codigoRegistroPk LIKE '%{$session->get('filtroGenLogCodigoRegistro')}%' ");
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

        return $queryBuilder;
    }

//    public function listaDql($codigo = null, $usuario = null, $accion = null, $modulo = null, $fecha = "", $hoy = false, $fechaHasta = "", $entidad = "")
//    {
//        $em = $this->getEntityManager();
//        $qb = $em->createQueryBuilder();
//        $qb->from("App:General\GenLog", "le")
//            ->select("le");
//        if(!empty($codigo)) {
//            $qb->andWhere("le.codigoRegistroPk = '{$codigo}'");
//        }
//        if(!empty($usuario)) {
//            $qb->andWhere("le.codigoUsuarioFk = '{$usuario}'");
//        }
//        if(!empty($accion)) {
//            $qb->andWhere("le.accion LIKE '%{$accion}%'");
//        }
//        if(!empty($modulo)) {
//            $qb->andWhere("le.modulo LIKE '%{$modulo}%'");
//        }
//        if($hoy){
//            $hoy = new \DateTime('now');
//            $qb->andWhere("le.fecha LIKE '%{$hoy->format('Y-m-d')}%'");
//        }
//        if($fecha != ""){
//            $qb->andWhere("le.fecha >= '{$fecha} 00:00:00'");
//        }
//        if($fechaHasta != ""){
//            $qb->andWhere("le.fecha <= '{$fechaHasta} 23:59:59'");
//        }
//        if($entidad != ""){
//            $qb->andWhere("le.nombreEntidad LIKE '%{$entidad}%'");
//        }
//        $qb->orderBy("le.fecha", "desc");
//        return $qb->getQuery()->getDQL();
//    }
//
//    /**
//     * @param $arLog GenLog
//     */
//    public function listaCambios()
//    {
//        $em = $this->getEntityManager();
//        $qb = $em->createQueryBuilder();
//        $qb->from("App:General\GenLog", "le")
//            ->select("le");
//        $qb->orderBy("le.fecha", "asc");
//        return $qb->getQuery()->getDQL();
//    }
}