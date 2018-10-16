<?php

namespace App\Repository\General;

use App\Entity\General\GenLog;
use App\Entity\General\GenSexo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class GenLogRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GenSexo::class);
    }


    public function getCodigoPadre($codigoRegistro)
    {
        try {
            $em = $this->getEntityManager();
            $qb = $em->createQueryBuilder();
            $qb->from("App:General\GenLog", "le")
                ->select("le")
                ->where("le.codigoRegistroPk = '{$codigoRegistro}'")
                ->orderBy("le.fecha", "desc")
                ->setMaxResults(1);
            $resultado = $qb->getQuery()->getOneOrNullResult();
            return $resultado? $resultado : null;
        } catch(ORMException $e){
            return null;
        }
    }

    public function listaDql($codigo = null, $usuario = null, $accion = null, $modulo = null, $fecha = "", $hoy = false, $fechaHasta = "", $entidad = "")
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->from("App:General\GenLog", "le")
            ->select("le");
        if(!empty($codigo)) {
            $qb->andWhere("le.codigoRegistroPk = '{$codigo}'");
        }
        if(!empty($usuario)) {
            $qb->andWhere("le.codigoUsuarioFk = '{$usuario}'");
        }
        if(!empty($accion)) {
            $qb->andWhere("le.accion LIKE '%{$accion}%'");
        }
        if(!empty($modulo)) {
            $qb->andWhere("le.modulo LIKE '%{$modulo}%'");
        }
        if($hoy){
            $hoy = new \DateTime('now');
            $qb->andWhere("le.fecha LIKE '%{$hoy->format('Y-m-d')}%'");
        }
        if($fecha != ""){
            $qb->andWhere("le.fecha >= '{$fecha} 00:00:00'");
        }
        if($fechaHasta != ""){
            $qb->andWhere("le.fecha <= '{$fechaHasta} 23:59:59'");
        }
        if($entidad != ""){
            $qb->andWhere("le.nombreEntidad LIKE '%{$entidad}%'");
        }
        $qb->orderBy("le.fecha", "desc");
        return $qb->getQuery()->getDQL();
    }

    /**
     * @param $arLog GenLog
     */
    public function listaCambios($arLog)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->from("App:General\GenLog", "le")
            ->select("le");
        if($arLog->getCodigoPadre() != null) {
            $qb->where("le.codigoLogPk = '{$arLog->getCodigoPadre()}'")
                ->orWhere("le.codigoPadre = '{$arLog->getCodigoPadre()}'");
        }
        $qb->orderBy("le.fecha", "asc");
        return $qb->getQuery()->getDQL();
    }
}