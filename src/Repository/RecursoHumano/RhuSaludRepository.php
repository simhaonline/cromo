<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuSalud;
use App\Utilidades\Mensajes;
use Brasa\RecursoHumanoBundle\Entity\RhuContratoTipo;
use Brasa\RecursoHumanoBundle\Entity\RhuTiempo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuSaludRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuSalud::class);
    }

    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->select('s.codigoSaludPk AS ID')
            ->addSelect('s.nombre')
            ->addSelect('s.porcentajeEmpleado')
            ->addSelect('s.porcentajeEmpleador')
            ->addSelect('s.codigoConceptoFk')
            ->addSelect('s.orden')
            ->from(RhuSalud::class,'s')
            ->where('s.codigoSaludPk IS NOT NULL')->getQuery()->execute();
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoSalud = null;
        $nombre = null;

        if ($filtros) {
            $codigoSalud = $filtros['codigoSalud'] ?? null;
            $nombre = $filtros['nombre'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuSalud::class, 's')
            ->select('s.codigoSaludPk')
            ->addSelect('s.nombre')
            ->addSelect('s.porcentajeEmpleado')
            ->addSelect('s.porcentajeEmpleador')
            ->addSelect('s.orden')
            ->addSelect('c.nombre as concepto')
            ->leftJoin('s.conceptoRel', 'c');

        if ($codigoSalud) {
            $queryBuilder->andWhere("s.codigoSaludPk = '{$codigoSalud}'");
        }
        if ($nombre) {
            $queryBuilder->andWhere("s.nombre LIKE '%{$nombre}%'");
        }


        $queryBuilder->addOrderBy('s.codigoSaludPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function eliminar($arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrDetallesSeleccionados) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $ar = $em->getRepository(RhuSalud::class)->find($codigo);
                    if ($ar) {
                        $em->remove($ar);
                    }
                }
                try {
                    $em->flush();
                } catch (\Exception $e) {
                    Mensajes::error('No se puede eliminar, el registro se encuentra en uso en el sistema');
                }
            }
        }
    }
}