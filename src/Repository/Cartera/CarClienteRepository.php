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
        $qb->select('cc.codigoClientePk AS ID')
            ->join("cc.formaPagoRel", "fp")
            ->join('cc.ciudadRel', 'c')
            ->addSelect('cc.digitoVerificacion AS DIGITO')
            ->addSelect('cc.nit AS NIT')
            ->addSelect('c.nombre AS CIUDAD')
            ->addSelect('cc.nombreCorto AS NOMBRE')
            ->addSelect('fp.nombre AS FORMA_PAGO')
            ->addSelect('cc.plazoPago AS PLAZO')
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