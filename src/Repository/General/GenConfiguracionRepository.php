<?php

namespace App\Repository\General;

use App\Entity\General\GenConfiguracion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class GenConfiguracionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GenConfiguracion::class);
    }

    public function impresionFormato(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT c.nit, 
        c.digitoVerificacion,
        c.nombre,
        c.direccion,
        c.telefono
        FROM App\Entity\General\GenConfiguracion c 
        WHERE c.codigoConfiguracionPk = :codigoConfiguracion'
        )->setParameter('codigoConfiguracion', 1);
        return $query->getSingleResult();

    }

    public function parametro($campo): string
    {
        $em = $this->getEntityManager();
        $dato = "";
        $query = $em->createQuery(
            "SELECT c.".$campo."
        FROM App\Entity\General\GenConfiguracion c 
        WHERE c.codigoConfiguracionPk = :codigoConfiguracion"
        )->setParameter('codigoConfiguracion', 1);
        $arConfiguracion = $query->getSingleResult();
        if($arConfiguracion) {
            $dato = $arConfiguracion[$campo];
        }
        return $dato;

    }

    public function invLiquidarMovimiento()
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(GenConfiguracion::class, 'c')
            ->select('c.autoretencionVenta')
            ->addSelect('c.porcentajeAutoretencion')
            ->addSelect('c.codigoCuentaAutoretencionVentaFk')
            ->addSelect('c.codigoCuentaAutoretencionVentaValorFk')
            ->where('c.codigoConfiguracionPk = 1');

        return $queryBuilder->getQuery()->getSingleResult();
    }

    public function contabilidadAutomatica()
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(GenConfiguracion::class, 'c')
            ->select('c.contabilidadAutomatica')
            ->where('c.codigoConfiguracionPk = 1');

        return $queryBuilder->getQuery()->getSingleResult();
    }

}