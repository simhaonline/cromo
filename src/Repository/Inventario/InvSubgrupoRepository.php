<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvSubgrupo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;

class InvSubgrupoRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InvSubgrupo::class);
    }

    public function camposPredeterminados()
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('m.codigoSubgrupoPk AS ID')
            ->addSelect("m.nombre AS NOMBRE")
            ->from("App:Inventario\InvSubgrupo", "m")
            ->where('m.codigoSubgrupoPk IS NOT NULL');
        $qb->orderBy('m.codigoSubgrupoPk', 'ASC');
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

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvSubgrupo::class, 'sg')
            ->select('sg.codigoSubgrupoPk')
            ->addSelect('sg.nombre');


        if ($codigo) {
            $queryBuilder->andWhere("sg.codigoSubgrupoPk = '{$codigo}'");
        }

        if($nombre){
            $queryBuilder->andWhere("sg.nombre LIKE '%{$nombre}%'");
        }

        $queryBuilder->addOrderBy('sg.codigoSubgrupoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();

    }

    public function eliminar($arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrDetallesSeleccionados) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $arRegistro = $em->getRepository(InvSubgrupo::class)->find($codigo);
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