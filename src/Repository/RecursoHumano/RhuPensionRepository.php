<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuPension;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuPensionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuPension::class);
    }

    public function camposPredeterminados(){
        return $this->_em->createQueryBuilder()
            ->select('p.codigoPensionPk AS ID')
            ->addSelect('p.nombre')
            ->addSelect('p.porcentajeEmpleado')
            ->addSelect('p.porcentajeEmpleador')
            ->addSelect('p.codigoConceptoFk')
            ->addSelect('p.orden')
            ->from(RhuPension::class,'p')
            ->where('p.codigoPensionPk IS NOT NULL')->getQuery()->execute();
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoPension = null;
        $nombre = null;

        if ($filtros) {
            $codigoPension = $filtros['codigoPension'] ?? null;
            $nombre = $filtros['nombre'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuPension::class, 'p')
            ->select('p.codigoPensionPk')
            ->addSelect('p.nombre')
            ->addSelect('p.porcentajeEmpleado')
            ->addSelect('p.porcentajeEmpleador')
            ->addSelect('p.orden')
            ->addSelect('c.nombre as concepto')
            ->leftJoin('p.conceptoRel', 'c');

        if ($codigoPension) {
            $queryBuilder->andWhere("p.codigoPensionPk = '{$codigoPension}'");
        }

        if ($nombre) {
            $queryBuilder->andWhere("p.nombre LIKE '%{$nombre}%'");
        }


        $queryBuilder->addOrderBy('p.codigoPensionPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function eliminar($arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrDetallesSeleccionados) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $ar = $em->getRepository(RhuPension::class)->find($codigo);
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