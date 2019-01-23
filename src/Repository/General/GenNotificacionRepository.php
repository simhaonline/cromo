<?php

namespace App\Repository\General;

use App\Entity\General\GenNotificacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GenNotificacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method GenNotificacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method GenNotificacion[]    findAll()
 * @method GenNotificacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GenNotificacionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GenNotificacion::class);
    }


    public function notificaciones($codigoUsuario){
        $em=$this->getEntityManager();
        $arNotificacion=$em->createQueryBuilder()
            ->from('App:General\GenNotificacion','n')
            ->join('n.notificacionTipoRel','nt')
            ->select('nt.nombre')
            ->addSelect('n.fecha')
            ->where("n.codigoUsuarioReceptorFk='{$codigoUsuario}'")
            ->orderBy('n.fecha','DESC')
            ->getQuery()->getResult();
        for ( $i=0; $i<count($arNotificacion);$i++){
            $arNotificacion[$i]['fecha']=$arNotificacion[$i]['fecha']->format('YmdHis');
        }
        return $arNotificacion;
    }

    public function lista($id){
        $em=$this->getEntityManager();
        $arNotificacion=$em->createQueryBuilder()
            ->from('App:General\GenNotificacion','n')
            ->select('n.codigoNotificacionPk as id')
            ->addSelect('nt.nombre')
            ->addSelect('n.fecha')
            ->leftJoin('n.notificacionTipoRel','nt')
            ->andWhere("n.codigoUsuarioReceptorFk='{$id}'")
            ->orderBy("n.fecha", "DESC");

        return $arNotificacion;
    }
}
