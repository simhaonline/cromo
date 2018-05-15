<?php

namespace App\Repository\General;

use App\Entity\General\GenCubo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class GenCuboRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GenCubo::class);
    }

    /**
     * @param $arrRegistros
     * @param $entidadCubo
     * @throws \Doctrine\ORM\ORMException
     */
    public function sqlCubo($arrRegistros, $entidadCubo)
    {
        $em = $this->getEntityManager();
        $arConfiguracionEntidadCubo = $em->getRepository("App:General\GenConfiguracionEntidad")->find($entidadCubo);
        $qb = $em->createQueryBuilder()->from($arConfiguracionEntidadCubo->getRutaEntidad(), "tbl");
        foreach ($arrRegistros['columnas'] as $columna) {
            if (strpos($columna, 'Rel')) {
                $relacion = lcfirst($columna);
                $qb->leftJoin("tbl.{$relacion}", "$relacion")
                    ->addSelect("{$relacion}.nombre AS {$columna}");
            } else {
                $qb->addSelect("tbl.$columna");
            }
        }
        foreach ($arrRegistros['orden'] as $orden) {
            $qb->addOrderBy("tbl.$orden", $arrRegistros['tipoOrden']);
        }

        return $qb->getDQL();
    }

}