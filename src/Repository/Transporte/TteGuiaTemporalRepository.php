<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteGuiaTemporal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteGuiaTemporalRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteGuiaTemporal::class);
    }

    public function lista()
    {
        $qb = $this->_em->createQueryBuilder()
            ->select('g.codigoGuiaPk')
            ->addSelect('g.numero')
            ->addSelect('g.fechaIngreso')
            ->addSelect('g.clienteDocumento')
            ->addSelect('g.destinatarioNombre')
            ->addSelect('g.codigoCiudadOrigenFk')
            ->addSelect('g.codigoCiudadDestinoFk')
            ->addSelect('g.unidades')
            ->addSelect('g.pesoFacturado')
            ->addSelect('g.vrDeclara')
            ->addSelect('g.vrFlete')
            ->addSelect('g.vrManejo')
            ->from(TteGuiaTemporal::class, 'g')
            ->where('g.codigoGuiaPk <> 0');
        return $qb;
    }
}
