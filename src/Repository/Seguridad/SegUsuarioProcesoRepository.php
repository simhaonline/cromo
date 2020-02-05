<?php

namespace App\Repository\Seguridad;

use App\Entity\Seguridad\SegUsuarioModelo;
use App\Entity\Seguridad\SegUsuarioProceso;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method SegUsuarioModelo|null find($id, $lockMode = null, $lockVersion = null)
 * @method SegUsuarioModelo|null findOneBy(array $criteria, array $orderBy = null)
 * @method SegUsuarioModelo[]    findAll()
 * @method SegUsuarioModelo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SegUsuarioProcesoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SegUsuarioProceso::class);
    }

    public function lista($idUsuario)
    {
        $em = $this->getEntityManager();
        $strSql = "SELECT codigo_seguridad_usuario_proceso_pk ,
                        codigo_proceso_fk,
                        codigo_usuario_fk, 
                        nombre, codigo_modulo_fk
                        FROM
                        seg_usuario_proceso
                        LEFT JOIN gen_proceso 
                        ON seg_usuario_proceso.codigo_proceso_fk  = gen_proceso.codigo_proceso_pk
                        WHERE  codigo_usuario_fk = '{$idUsuario}'";
        $connection = $em->getConnection();
        $statement = $connection->prepare($strSql);
        $statement->execute();
        $results = $statement->fetchAll();
        return $results;


        return $arSeguridadUsuarioProceso;
    }

}
