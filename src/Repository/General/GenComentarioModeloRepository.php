<?php

namespace App\Repository\General;


use App\Entity\General\GenComentarioModelo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class GenComentarioModeloRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GenComentarioModelo::class);
    }

    public function lista($codigoModelo, $codigo)
    {
        return $this->_em->createQueryBuilder()->from(GenComentarioModelo::class, 'cm')
            ->select('cm')
            ->where("cm.codigoModeloFk = '{$codigoModelo}'")
            ->andWhere("cm.codigo = {$codigo}")
            ->orderBy("cm.fecha",'ASC')
            ->getQuery()->execute();
    }

    public function usuariosComentario($codigoModelo, $codigo)
    {
        $arrResultados =  $this->_em->createQueryBuilder()->from(GenComentarioModelo::class, 'cm')
            ->select('cm.codigoUsuario')
            ->where("cm.codigoModeloFk = '{$codigoModelo}'")
            ->andWhere("cm.codigo = {$codigo}")
            ->groupBy('cm.codigoUsuario')
            ->getQuery()->getResult();
        $arrRespuesta = array();
        foreach ($arrResultados as $arrResultado) {
            $arrRespuesta[] = $arrResultado['codigoUsuario'];
        }
        return $arrRespuesta;

    }

}