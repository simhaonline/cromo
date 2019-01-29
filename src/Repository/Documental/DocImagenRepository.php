<?php

namespace App\Repository\Documental;

use App\Entity\Documental\DocImagen;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;
class DocImagenRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DocImagen::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(DocImagen::class, 'm')
            ->select('m.codigoImagenPk')
            ->addSelect('m.identificador')
            ->where('m.codigoImagenPk <> 0');
        if ($session->get('filtroDocImagenIdentificador') != '') {
            $queryBuilder->andWhere("m.identificador = {$session->get('filtroDocImagenIdentificador')}");
        }
        return $queryBuilder;
    }

    public function listaArchivo($tipo, $codigo)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(DocImagen::class, 'm')
            ->select('m.codigoImagenPk')
            ->addSelect('m.identificador')
            ->where("m.codigoImagenTipoFk = '" . $tipo . "'")
            ->andWhere("m.identificador = '" . $codigo . "'");
        return $queryBuilder;
    }
}