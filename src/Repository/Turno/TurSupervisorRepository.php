<?php


namespace App\Repository\Turno;


use App\Entity\Turno\TurSupervisor;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class TurSupervisorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TurSupervisor::class);
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

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurSupervisor::class, 's')
            ->select('s.codigoSupervisorPk')
            ->addSelect('s.nombre')
            ->addSelect('s.celular')
            ->addSelect('s.correo')
            ->addSelect('s.numeroIdentificacion');
        if ($codigo) {
            $queryBuilder->andWhere("s.codigoSupervisorPk = '{$codigo}'");
        }

        if($nombre){
            $queryBuilder->andWhere("s.nombre LIKE '%{$nombre}%'");
        }

        $queryBuilder->addOrderBy('s.codigoSupervisorPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function eliminar($arrSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $em->getRepository(TurSupervisor::class)->find($codigo);
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