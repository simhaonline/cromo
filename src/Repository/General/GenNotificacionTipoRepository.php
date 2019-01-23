<?php

namespace App\Repository\General;

use App\Entity\General\GenNotificacionTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @method GenNotificacionTipo|null find($id, $lockMode = null, $lockVersion = null)
 * @method GenNotificacionTipo|null findOneBy(array $criteria, array $orderBy = null)
 * @method GenNotificacionTipo[]    findAll()
 * @method GenNotificacionTipo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GenNotificacionTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GenNotificacionTipo::class);
    }

    public function lista(){
        $em=$this->getEntityManager();
        $session= new Session();

        $arNotificacionTipo= $em->createQueryBuilder()
            ->from('App:General\GenNotificacionTipo','nt')
            ->select('nt.codigoNotificacionTipoPk')
            ->addSelect('nt.estadoActivo')
            ->addSelect('nt.nombre')
            ->addSelect('nt.usuarios');

        if($session->get('arGenNotificacionTipoFiltroModulo')!="" && $session->get('arGenNotificacionTipoFiltroModulo')!=null){
            $modulo=$session->get('arGenNotificacionTipoFiltroModulo')->getCodigoModuloPk();
            $arNotificacionTipo=$arNotificacionTipo->andWhere("m.codigoModuloFk='{$modulo}'");
        }

        if($session->get('arGenNotificacionTipoFiltroModelo')!="" && $session->get('arGenNotificacionTipoFiltroModelo')!=null){
            $arNotificacionTipo=$arNotificacionTipo->andWhere("m.codigoModeloPk='{$session->get('arGenNotificacionTipoFiltroModelo')}'");
        }

        $arNotificacionTipo=$arNotificacionTipo->getQuery()->getResult();

        return $arNotificacionTipo;
    }

    public function listaUsuarios($codigoUsuario){
        $session = new Session();
        $em=$this->getEntityManager();
        $arUsuarios=$em->createQueryBuilder()
            ->from('App:Seguridad\Usuario','u')
            ->select('u.username')
            ->addSelect('u.nombreCorto')
            ->addSelect('u.username');

            if($session->get('arGenNotificacionTipoNombreUsuario')!=="" || $session->get('arGenNotificacionTipoNombreUsuario')!==null){
                $arUsuarios=$arUsuarios->andWhere("u.username LIKE '%{$session->get('arGenNotificacionTipoNombreUsuario')}%'");
            }
            $arUsuarios=$arUsuarios->getQuery()->getResult();

        return $arUsuarios;
    }

//    /**
//     * @return GenNotificacionTipo[] Returns an array of GenNotificacionTipo objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GenNotificacionTipo
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
