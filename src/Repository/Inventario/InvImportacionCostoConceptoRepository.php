<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvImportacionCostoConcepto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class InvImportacionCostoConceptoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InvImportacionCostoConcepto::class);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function camposPredeterminados(){
        return $this->_em->createQueryBuilder()
            ->select('icc.codigoImportacionCostoConceptoPk AS ID')
            ->addSelect('icc.nombre')
            ->from(InvImportacionCostoConcepto::class,'icc')
            ->where('icc.codigoImportacionCostoConceptoPk IS NOT NULL')->getQuery()->execute();
    }


    public function  lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigo = null;
        $nombre = null;


        if ($filtros) {
            $codigo = $filtros['codigo'] ?? null;
            $nombre = $filtros['nombre'] ?? null;


        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvImportacionCostoConcepto::class, 'ic')
            ->select('ic.codigoImportacionCostoConceptoPk')
            ->addSelect('ic.nombre');


        if ($codigo) {
            $queryBuilder->andWhere("ic.codigoImportacionCostoConceptoPk = '{$codigo}'");
        }

        if($nombre){
            $queryBuilder->andWhere("ic.nombre LIKE '%{$nombre}%'");
        }

        $queryBuilder->addOrderBy('ic.codigoImportacionCostoConceptoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();

    }

    public function eliminar($arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrDetallesSeleccionados) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $arRegistro = $em->getRepository(InvImportacionCostoConcepto::class)->find($codigo);
                    if ($arRegistro) {
                        $em->remove($arRegistro);
                    }
                }
                try {
                    $em->flush();
                } catch (\Exception $e) {
                    Mensajes::error('No se puede eliminar, el registro se encuentra en uso en el sistema');
                }
            }
        }else{
            Mensajes::error("No existen registros para eliminar");
        }
    }


}
