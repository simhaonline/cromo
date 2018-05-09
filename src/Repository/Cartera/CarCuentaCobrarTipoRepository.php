<?php

namespace App\Repository\Cartera;

use App\Entity\Cartera\CarCuentaCobrarTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CarCuentaCobrarTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CarCuentaCobrarTipo::class);
    }

    public function lista(): array
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder()->from(CarCuentaCobrarTipo::class, 'cct');
        $qb->select('cct.codigoCuentaCobrarTipoPk')
            ->addSelect('cct.nombre')
            ->addSelect('cct.codigoCuentaClienteFk')
            ->addSelect('cct.codigoCuentaRetencionFuenteFk')
            ->addSelect('cct.codigoCuentaRetencionIcaFk')
            ->addSelect('cct.codigoCuentaRetencionIvaFk')
            ->addSelect('cct.tipoCuentaCliente')
            ->where('cct.codigoCuentaCobrarTipoPk <> 0')
            ->orderBy('cct.codigoCuentaCobrarTipoPk', 'DESC');
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
                $ar = $em->getRepository(CarCuentaCobrarTipo::class)->find($codigo);
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