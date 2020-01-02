<?php


namespace App\Repository\RecursoHumano;


use App\Entity\RecursoHumano\RhuLicenciaTipo;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuLicenciaTipoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuLicenciaTipo::class);
    }


    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoLicenciaTipo = null;
        $nombre = null;

        if ($filtros) {
            $codigoLicenciaTipo = $filtros['codigoLicenciaTipo'] ?? null;
            $nombre = $filtros['nombre'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuLicenciaTipo::class, 'lt')
            ->select('lt.codigoLicenciaTipoPk')
            ->addSelect('lt.nombre')
            ->addSelect('lt.afectaSalud')
            ->addSelect('lt.ausentismo')
            ->addSelect('lt.maternidad')
            ->addSelect('lt.paternidad')
            ->addSelect('lt.remunerada')
            ->addSelect('lt.suspensionContratoTrabajo');

        if ($codigoLicenciaTipo) {
            $queryBuilder->andWhere("lt.codigoLicenciaTipoPk = '{$codigoLicenciaTipo}'");
        }

        if ($nombre) {
            $queryBuilder->andWhere("lt.nombre LIKE '%{$nombre}%'");
        }


        $queryBuilder->addOrderBy('lt.codigoLicenciaTipoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function eliminar($arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrDetallesSeleccionados) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $ar = $em->getRepository(RhuLicenciaTipo::class)->find($codigo);
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