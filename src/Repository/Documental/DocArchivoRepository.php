<?php

namespace App\Repository\Documental;

use App\Entity\Documental\DocArchivo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;
class DocArchivoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DocArchivo::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(DocMasivo::class, 'm')
            ->select('m.codigoMasivoPk')
            ->addSelect('m.identificador')
            ->where('m.codigoMasivoPk <> 0');
        if ($session->get('filtroDocMasivoIdentificador') != '') {
            $queryBuilder->andWhere("m.identificador = {$session->get('filtroDocMasivoIdentificador')}");
        }
        return $queryBuilder;
    }

    public function listaArchivo($tipo, $codigo)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(DocMasivo::class, 'm')
            ->select('m.codigoMasivoPk')
            ->addSelect('m.identificador')
            ->where("m.codigoMasivoTipoFk = '" . $tipo . "'")
            ->andWhere("m.identificador = '" . $codigo . "'");
        return $queryBuilder;
    }
}