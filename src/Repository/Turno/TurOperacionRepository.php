<?php


namespace App\Repository\Turno;


use App\Entity\Turno\TurOperacion;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class TurOperacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TurOperacion::class);
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

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurOperacion::class, 'o')
            ->select('o.codigoOperacionPk')
            ->addSelect('o.nombre')
            ->addSelect('o.nombreCorto')
            ->addSelect('c.nombreCorto as cliente')
            ->leftJoin('o.clienteRel', 'c');

        if ($codigo) {
            $queryBuilder->andWhere("o.codigoOperacionPk = '{$codigo}'");
        }

        if($nombre){
            $queryBuilder->andWhere("o.nombre LIKE '%{$nombre}%'");
        }

        $queryBuilder->addOrderBy('o.codigoOperacionPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function eliminar($arrSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $em->getRepository(TurOperacion::class)->find($codigo);
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