<?php

namespace App\Repository\Crm;

use App\Entity\Crm\CrmVisita;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CrmVisita|null find($id, $lockMode = null, $lockVersion = null)
 * @method CrmVisita|null findOneBy(array $criteria, array $orderBy = null)
 * @method CrmVisita[]    findAll()
 * @method CrmVisita[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CrmVisitaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CrmVisita::class);
    }

    public function eliminar($arrSeleccionados)
    {
        $respuesta = '';
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $this->getEntityManager()->getRepository(CrmVisita::class)->find($codigo);
                if ($arRegistro) {
                    if ($arRegistro->getEstadoAprobado() == 0) {
                        if ($arRegistro->getEstadoAutorizado() != 0) {
                            $respuesta = 'No se puede eliminar, el registro se encuentra autorizado';
                        }
                    } else {
                        $respuesta = 'No se puede eliminar, el registro se encuentra aprobado';
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

    /**
     * @param $arVisita CrmVisita
     */
    public function autorizar($arVisita)
    {
        $em = $this->getEntityManager();
        if($arVisita->getEstadoAutorizado() == 0) {

                $arVisita->setEstadoAutorizado(1);
                $em->persist($arVisita);
                $em->flush();

        } else {
            Mensajes::error('La visita ya esta autorizado');
        }
    }

    /**
     * @param $arVisita CrmVisita
     */
    public function desautorizar($arVisita){
        $em = $this->getEntityManager();
        if($arVisita->getEstadoAutorizado() == 1) {

            $arVisita->setEstadoAutorizado(0);
            $em->persist($arVisita);
            $em->flush();

        } else {
            Mensajes::error('La visita ya esta desautorizada');
        }
    }

    /**
     * @param $arVisita CrmVisita
     */
    public function aprobar($arVisita){
        $em = $this->getEntityManager();
        if($arVisita->getEstadoAutorizado() == 1 ) {
            if($arVisita->getEstadoAprobado() == 0){
                $arVisita->setEstadoAprobado(1);
                $em->persist($arVisita);
                $em->flush();
            }else{
                Mensajes::error('La visita ya esta aprobada');
            }

        } else {
            Mensajes::error('La visita ya esta desautorizada');
        }
    }

    public function anular($arVisita){
        $em = $this->getEntityManager();
        if($arVisita->getEstadoAutorizado() == 1 ) {
            if($arVisita->getEstadoAnulado() == 0){
                $arVisita->setEstadoAnulado(1);
                $em->persist($arVisita);
                $em->flush();
            }else{
                Mensajes::error('La visita ya esta anulado');
            }

        } else {
            Mensajes::error('La visita no esta autorizada');
        }
    }

//    /**
//     * @return CrmVisita[] Returns an array of CrmVisita objects
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
    public function findOneBySomeField($value): ?CrmVisita
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
