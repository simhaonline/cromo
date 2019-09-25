<?php

namespace App\Repository\Cartera;

use App\Entity\Cartera\CarIngresoDetalle;
use App\Entity\Tesoreria\TesEgresoDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class CarIngresoDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CarIngresoDetalle::class);
    }

    public function lista($codigoIngreso)
    {
        $session = new Session();
        $queryBuilder = $this->_em->createQueryBuilder()->from(CarIngresoDetalle::class, 'id')
            ->select('id.codigoIngresoDetallePk')
            ->addSelect('id.numero')
            ->addSelect('id.codigoCuentaCobrarFk')
            ->addSelect('cc.codigoCuentaCobrarTipoFk')
            ->addSelect('c.nombreCorto as clienteNombreCorto')
            ->addSelect('c.numeroIdentificacion as clienteNumeroIdentifiacion')
            ->addSelect('cc.vrSaldo')
            ->addSelect('id.vrPago')
            ->addSelect('id.codigoCuentaFk')
            ->addSelect('id.codigoClienteFk')
            ->addSelect('id.naturaleza')
            ->leftJoin('id.cuentaCobrarRel', 'cc')
            ->leftJoin('id.clienteRel', 'c')
            ->where("id.codigoIngresoFk = '{$codigoIngreso}'");
        return $queryBuilder;
    }

}