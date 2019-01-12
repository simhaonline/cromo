<?php

namespace App\Repository\Crm;

use App\Entity\Crm\CrmVisitaTipo;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CrmVisitaTipo|null find($id, $lockMode = null, $lockVersion = null)
 * @method CrmVisitaTipo|null findOneBy(array $criteria, array $orderBy = null)
 * @method CrmVisitaTipo[]    findAll()
 * @method CrmVisitaTipo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CrmVisitaTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CrmVisitaTipo::class);
    }

    public function eliminar($arrSeleccionados)
    {
        $respuesta = '';
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $this->getEntityManager()->getRepository(CrmVisitaTipo::class)->find($codigo);
                if ($arRegistro) {
                    $arServicio=$this->getEntityManager()->getRepository('App:Crm\CrmVisita')->findBy(['codigoVisitaTipoFk'=>$codigo]);
                    if($arServicio){
                        $respuesta='No se puede eliminar el registro, esta siendo utilizado en uno o mas visitas';
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
//     * @return CrmVisitaTipo[] Returns an array of CrmVisitaTipo objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CrmVisitaTipo
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
