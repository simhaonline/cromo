<?php

namespace App\Repository;

use App\Entity\Despacho;
use App\Entity\Guia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class DespachoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Despacho::class);
    }

    public function lista(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT d.codigoDespachoPk, 
        d.numero,
        d.codigoOperacionFk,
        d.codigoVehiculoFk,
        d.codigoRutaFk, 
        co.nombre AS ciudadOrigen, 
        cd.nombre AS ciudadDestino,
        d.unidades,
        d.pesoReal,
        d.pesoVolumen,
        d.vrFlete,
        d.vrManejo,
        d.vrDeclara
        FROM App\Entity\Despacho d         
        LEFT JOIN d.ciudadOrigenRel co
        LEFT JOIN d.ciudadDestinoRel cd'
        );
        return $query->execute();

    }

    public function retirarGuia($arrGuias): array
    {
        $em = $this->getEntityManager();
        if($arrGuias) {
            if (count($arrGuias) > 0) {
                foreach ($arrGuias AS $codigoGuia) {
                    $arGuia = $em->getRepository(Guia::class)->find($codigoGuia);
                    $arGuia->setDespachoRel(null);
                    $arGuia->setEstadoDespachado(0);
                    $em->persist($arGuia);
                }
                $em->flush();
            }
        }
    }
}