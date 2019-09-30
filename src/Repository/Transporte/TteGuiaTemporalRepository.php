<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteGuiaTemporal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

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

    public function importarExcel()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteGuiaTemporal::class, 'g')
            ->select('g.codigoGuiaPk')
            ->addSelect('g.numero')
            ->addSelect('g.fechaIngreso')
            ->addSelect('g.clienteDocumento')
            ->addSelect('g.clienteRelacion')
            ->addSelect('g.destinatarioNombre')
            ->addSelect('co.nombre as ciudadOrigen')
            ->addSelect('cd.nombre as ciudadDestino')
            ->addSelect('c.nombreCorto As cliente')
            ->addSelect('g.unidades')
            ->addSelect('g.pesoFacturado')
            ->addSelect('g.vrDeclara')
            ->addSelect('g.vrFlete')
            ->addSelect('g.vrManejo')
            ->addSelect('p.nombre as productoNombre')
            ->addSelect('g.tipoLiquidacion')
            ->leftJoin('g.clienteRel', 'c')
            ->leftJoin('g.ciudadOrigenRel', 'co')
            ->leftJoin('g.ciudadDestinoRel', 'cd')
            ->leftJoin('g.productoRel', 'p')
            ->where('g.codigoGuiaPk <> 0')
            ->andWhere("g.origen = 'E'");
        return $queryBuilder;
    }

    public function eliminar($arrSeleccionados)
    {
        foreach ($arrSeleccionados as $arrSeleccionado) {
            $arGuiaTemporal = $this->getEntityManager()->getRepository(TteGuiaTemporal::class)->find($arrSeleccionado);
            if ($arGuiaTemporal) {
                if ($arGuiaTemporal->getOrigen() == "E") {
                    $this->getEntityManager()->remove($arGuiaTemporal);
                }
            }
        }
        $this->getEntityManager()->flush();
    }
}
