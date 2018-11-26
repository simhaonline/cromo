<?php

namespace App\Repository\Financiero;

use App\Entity\Financiero\FinTercero;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class FinTerceroRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FinTercero::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(FinTercero::class, 't')
            ->select('t.codigoTerceroPk')
            ->addSelect('t.nombreCorto')
            ->addSelect('t.numeroIdentificacion')
            ->where('t.codigoTerceroPk <> 0')
            ->orderBy('t.codigoTerceroPk', 'DESC');
        if ($session->get('filtroCtbNombreTercero') != '') {
            $queryBuilder->andWhere("t.nombreCorto LIKE '%{$session->get('filtroCtbNombreTercero')}%' ");
        }
        if ($session->get('filtroNitTercero') != '') {
            $queryBuilder->andWhere("t.numeroIdentificacion LIKE '%{$session->get('filtroNitTercero')}%' ");
        }
        if ($session->get('filtroCtbCodigoTercero') != '') {
            $queryBuilder->andWhere("t.codigoTerceroPk LIKE '%{$session->get('filtroCtbCodigoTercero')}%' ");
        }

        return $queryBuilder;
    }

    public function listaIntercambio()
    {
        return $this->_em->createQueryBuilder()->from(FinTercero::class, 't')
            ->leftJoin('t.ciudadRel', 'c')
            ->leftJoin('c.departamentoRel', 'd')
            ->select('t.codigoIdentificacionFk')
            ->addSelect('t.nombreCorto')
            ->addSelect('t.nombre1')
            ->addSelect('t.nombre2')
            ->addSelect('t.apellido1')
            ->addSelect('t.apellido2')
            ->addSelect('t.direccion')
            ->addSelect('c.nombre as ciudadNombre')
            ->addSelect('c.codigoDane as codigoDaneCiudad')
            ->addSelect('t.telefono')
            ->addSelect('d.codigoDane as codigoDaneDepartamento')
            ->addSelect('t.email')
            ->addSelect('t.celular')
            ->addSelect('t.numeroIdentificacion');
    }
}