<?php

namespace App\Repository\Documental;

use App\Entity\Documental\DocArchivo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;
class DocArchivoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
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
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(DocArchivo::class, 'a')
            ->select('a.codigoArchivoPk')
            ->addSelect('a.codigo')
            ->addSelect('a.nombre')
            ->addSelect('a.fecha')
            ->addSelect('a.descripcion')
            ->addSelect('a.usuario')
            ->where("a.codigoArchivoTipoFk = '" . $tipo . "'")
            ->andWhere("a.codigo = '" . $codigo . "'");
        return $queryBuilder;
    }
}