<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuSeleccion;
use App\Entity\RecursoHumano\RhuSeleccionTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuSeleccionTipoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuSeleccionTipo::class);
    }


    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoSeleccionTipo = null;
        $nombre = null;

        if ($filtros) {
            $codigoSeleccionTipo = $filtros['codigoSeleccionTipo'] ?? null;
            $nombre = $filtros['nombre'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuSeleccionTipo::class, 'st')
            ->select('st.codigoSeleccionTipoPk')
            ->addSelect('st.nombre');
        if ($codigoSeleccionTipo) {
            $queryBuilder->andWhere("st.codigoSeleccionTipoPk = {$codigoSeleccionTipo} ");
        }

        if($nombre){
            $queryBuilder->andWhere("st.nombre LIKE '%{$nombre}%'");
        }

        $queryBuilder->addOrderBy('st.codigoSeleccionTipoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function eliminar($arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrDetallesSeleccionados) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $ar = $em->getRepository(RhuSeleccionTipo::class)->find($codigo);
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

    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:RecursoHumano\RhuSeleccionTipo','st')
            ->select('st.codigoSeleccionTipoPk AS ID')
            ->addSelect('st.nombre');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }
}