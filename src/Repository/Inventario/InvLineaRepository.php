<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvLinea;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;

class InvLineaRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InvLinea::class);
    }

    public function camposPredeterminados()
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('m.codigoLineaPk AS ID')
            ->addSelect("m.nombre AS NOMBRE")
            ->from("App:Inventario\InvLinea", "m")
            ->where('m.codigoLineaPk IS NOT NULL');
        $qb->orderBy('m.codigoLineaPk', 'ASC');
        $dql = $this->getEntityManager()->createQuery($qb->getDQL());
        return $dql->execute();
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

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvLinea::class, 'il')
            ->select('il.codigoLineaPk')
            ->addSelect('il.nombre');


        if ($codigo) {
            $queryBuilder->andWhere("il.codigoLineaPk = '{$codigo}'");
        }

        if($nombre){
            $queryBuilder->andWhere("il.nombre LIKE '%{$nombre}%'");
        }

        $queryBuilder->addOrderBy('il.codigoLineaPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();

    }

    public function eliminar($arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrDetallesSeleccionados) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $arRegistro = $em->getRepository(InvLinea::class)->find($codigo);
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