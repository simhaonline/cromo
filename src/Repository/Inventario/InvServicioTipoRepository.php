<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvServicioTipo;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method InvServicioTipo|null find($id, $lockMode = null, $lockVersion = null)
 * @method InvServicioTipo|null findOneBy(array $criteria, array $orderBy = null)
 * @method InvServicioTipo[]    findAll()
 * @method InvServicioTipo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvServicioTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvServicioTipo::class);
    }

    public function eliminar($arrSeleccionados)
    {
        $respuesta = '';
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $this->getEntityManager()->getRepository(InvServicioTipo::class)->find($codigo);
                if ($arRegistro) {
                    $arServicio=$this->getEntityManager()->getRepository('App:Inventario\InvServicio')->findBy(['codigoServicioTipoFk'=>$codigo]);
                    if($arServicio){
                        $respuesta='No se puede eliminar el registro, esta siendo utilizado en uno o mas servicios';
                    }
                    if ($respuesta != '') {
                        Mensajes::error($respuesta);
                    } else {
                        $this->getEntityManager()->remove($arRegistro);
                        $this->getEntityManager()->flush();
                    }
                }
            }
        }
    }

//    /**
//     * @return TurServicioTipo[] Returns an array of TurServicioTipo objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TurServicioTipo
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
