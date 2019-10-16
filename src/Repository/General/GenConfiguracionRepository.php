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

    public function apiWindowsConfiguracion($raw) {
        $em = $this->getEntityManager();
        /** @var $arConfiguracion GenConfiguracion*/
        $arConfiguracion = $em->getRepository(GenConfiguracion::class)->find(1);
        $logo = base64_encode(stream_get_contents($arConfiguracion->getLogo()));
        if($arConfiguracion) {
            return [
                "logo" => $logo,
            ];
        } else {
            return ["error" => "No existe la configuracion"];
        }
    }

    public function planoAporte()
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(GenConfiguracion::class, 'c')
            ->select('c.rutaTemporal')
            ->addSelect('c.nombre')
            ->addSelect('c.codigoIdentificacionFk')
            ->addSelect('c.nit')
            ->addSelect('c.digitoVerificacion')
            ->where('c.codigoConfiguracionPk = 1');

        return $queryBuilder->getQuery()->getSingleResult();
    }

    public function facturaElectronica()
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(GenConfiguracion::class, 'c')
            ->select('c.nit')
            ->addSelect('c.feToken')
            ->addSelect('c.codigoTipoPersonaFk')
            ->where('c.codigoConfiguracionPk = 1');

        return $queryBuilder->getQuery()->getSingleResult();
    }
}