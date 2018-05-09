<?php

namespace App\Repository\General;

use App\Entity\General\GenConfiguracionEntidad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Zend\Json\Json;

class GenConfiguracionEntidadRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GenConfiguracionEntidad::class);
    }

    /**
     * @param $jsonLista
     * @param $arConfiguracionEntidad GenConfiguracionEntidad
     */
    public function lista($arConfiguracionEntidad, $opcion)
    {
        $qb = $this->_em->createQueryBuilder()->from($arConfiguracionEntidad->getRutaEntidad(), 'tbl');
        switch ($opcion){
            case 0: $arrLista = json_decode($arConfiguracionEntidad->getJsonLista());
                break;
            case 1: $arrLista = json_decode($arConfiguracionEntidad->getJsonExcel());
                break;
        }
        $qb->select('tbl.' . $arrLista[0]->campo . ' AS ID');
        $i = 0;
        foreach ($arrLista as $lista) {
            if ($lista->mostrar) {
                if (!strpos($lista->campo, 'Pk')) {
                    $qb->addSelect("tbl.{$lista->campo} AS {$lista->alias}");
                }
            }
            $i++;
        }
        $qb->orderBy('tbl.' . $arrLista[0]->campo);
        $arrRegistros = $this->_em->createQuery($qb->getDQL());
        return $arrRegistros->execute();
    }

}