<?php

namespace App\Repository\Seguridad;

use App\Entity\Seguridad\SegGrupoProceso;
use App\Entity\Seguridad\SegUsuarioModelo;
use App\Entity\Seguridad\SegUsuarioProceso;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * @method SegUsuarioModelo|null find($id, $lockMode = null, $lockVersion = null)
 * @method SegUsuarioModelo|null findOneBy(array $criteria, array $orderBy = null)
 * @method SegUsuarioModelo[]    findAll()
 * @method SegUsuarioModelo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SegGrupoProcesoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SegGrupoProceso::class);
    }

    public function lista($id)
    {
        $em = $this->getEntityManager();
        $strSql = "SELECT codigo_grupo_proceso_pk ,codigo_grupo_fk,codigo_proceso_fk, nombre, codigo_modulo_fk
                        FROM
                        seg_grupo_proceso
                        LEFT JOIN gen_proceso 
                        ON seg_grupo_proceso.codigo_proceso_fk  = gen_proceso.codigo_proceso_pk
                        WHERE  codigo_grupo_fk = {$id}";
        $connection = $em->getConnection();
        $statement = $connection->prepare($strSql);
        $statement->execute();
        $results = $statement->fetchAll();
        return $results;
    }

    public function eliminar($arrSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $em->getRepository(SegGrupoProceso::class)->find($codigo);
                if ($arRegistro) {
                    $em->remove($arRegistro);
                }
            }
            try {
                $em->flush();
            } catch (\Exception $e) {
                Mensajes::error('No se puede eliminar, el registro se encuentra en uso en el sistema');
            }
        }else{
            Mensajes::error("No existen registros para eliminar");
        }
    }

}
