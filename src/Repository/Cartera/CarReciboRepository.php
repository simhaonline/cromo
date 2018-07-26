<?php

namespace App\Repository\Cartera;


use App\Entity\Cartera\CarRecibo;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CarReciboRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CarRecibo::class);
    }

    public function lista(): array
    {
        $session = new Session();
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder()->from(CarRecibo::class, 'r');
        $qb->select('r.codigoReciboPk')
            ->leftJoin('r.clienteRel','cr')
            ->leftJoin('r.cuentaRel','c')
            ->addSelect('c.nombre')
            ->addSelect('cr.nombreCorto')
            ->addSelect('cr.nit')
            ->addSelect('r.numero')
            ->addSelect('r.fecha')
            ->addSelect('r.fechaPago')
            ->addSelect('r.codigoCuentaFk')
            ->addSelect('r.vrPagoTotal')
            ->addSelect('r.estadoAutorizado')
            ->addSelect('r.estadoAnulado')
            ->addSelect('r.estadoImpreso')
            ->where('r.codigoReciboPk <> 0')
            ->orderBy('r.codigoReciboPk', 'DESC');
        if ($session->get('filtroNumero')) {
            $qb->andWhere("r.numero = '{$session->get('filtroNumero')}'");
        }
        $query = $qb->getDQL();
        $query = $em->createQuery($query);

        return $query->execute();
    }

//    public function eliminar($arrSeleccionados)
//    {
//        $respuesta = '';
//        $em = $this->getEntityManager();
//        if ($arrSeleccionados) {
//            foreach ($arrSeleccionados AS $codigo) {
//                $ar = $em->getRepository(CarReciboTipo::class)->find($codigo);
//                if ($ar) {
//                    $em->remove($ar);
//                }
//            }
//            try {
//                $em->flush();
//            } catch (\Exception $exception) {
//                $respuesta = 'No se puede eliminar, el registro esta siendo utilizado en el sistema';
//            }
//        }
//        return $respuesta;
//    }
}