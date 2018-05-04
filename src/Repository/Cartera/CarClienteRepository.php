<?php

namespace App\Repository\Cartera;

use App\Entity\Cartera\CarCliente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CarClienteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CarCliente::class);
    }

    public function lista(): array
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder()->from(CarCliente::class, 'cc');
        $qb->select('cc.codigoClientePk')
            ->join("cc.formaPagoRel", "fp")
            ->join('cc.ciudadRel', 'c')
            ->addSelect('cc.digitoVerificacion')
            ->addSelect('cc.nit')
            ->addSelect('c.nombre AS ciudad')
            ->addSelect('cc.nombreCorto')
            ->addSelect('fp.nombre')
            ->addSelect('cc.plazoPago')
            ->where('cc.codigoClientePk <> 0')
            ->orderBy('cc.codigoClientePk', 'DESC');
        $query = $qb->getDQL();
        $query = $em->createQuery($query);

        return $query->execute();
    }

    public function eliminar($arrSeleccionados)
    {
        $respuesta = '';
        $em = $this->getEntityManager();
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository(CarCliente::class)->find($codigo);
                if ($ar) {
                    $em->remove($ar);
                }
            }
            try {
                $em->flush();
            } catch (\Exception $exception) {
                $respuesta = 'No se puede eliminar, el registro esta siendo utilizado en el sistema';
            }
        }
        return $respuesta;
    }

}