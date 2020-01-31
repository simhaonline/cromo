<?php

namespace App\Repository\Turno;

use App\Entity\Turno\TurContratoTipo;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TurContratoTipoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TurContratoTipo::class);
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

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurContratoTipo::class, 'ct')
            ->select('ct.codigoContratoTipoPk')
            ->addSelect('ct.nombre')
            ->where('ct.codigoContratoTipoPk IS NOT NULL');

        if ($codigo) {
            $queryBuilder->andWhere("ct.codigoContratoTipoPk = '{$codigo}'");
        }

        if($nombre){
            $queryBuilder->andWhere("ct.nombre LIKE '%{$nombre}%'");
        }

        $queryBuilder->addOrderBy('ct.codigoContratoTipoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function eliminar($arrSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $em->getRepository(TurContratoTipo::class)->find($codigo);
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

    public function camposPredeterminados()
    {
        $qb = $this->_em->createQueryBuilder()
            ->from('App:Turno\TurContratoTipo', 'ct')
            ->select('ct.codigoContratoTipoPk AS ID')
            ->addSelect('ct.nombre AS NOMBRE');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

}
