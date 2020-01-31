<?php


namespace App\Repository\Turno;

use App\Entity\Turno\TurCoordinador;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class TurCoordinadorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TurCoordinador::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigo = null;
        $nombre = null;

        if ($filtros) {
            $codigo = $filtros['codigo'] ?? null;
            $nombre = $filtros['nombre'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurCoordinador::class, 'c')
            ->select('c.codigoCoordinadorPk')
            ->addSelect('c.nombre')
            ->addSelect('c.celular')
            ->addSelect('c.correo')
            ->addSelect('c.numeroIdentificacion');
        if ($codigo) {
            $queryBuilder->andWhere("c.codigoCoordinadorPk = '{$codigo}'");
        }

        if($nombre){
            $queryBuilder->andWhere("c.nombre LIKE '%{$nombre}%'");
        }

        $queryBuilder->addOrderBy('c.codigoCoordinadorPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function eliminar($arrSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $em->getRepository(TurCoordinador::class)->find($codigo);
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