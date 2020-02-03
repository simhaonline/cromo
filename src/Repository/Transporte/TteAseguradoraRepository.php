<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteAseguradora;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class TteAseguradoraRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TteAseguradora::class);
    }

    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:Transporte\TteAseguradora','a')
            ->select('a.codigoAseguradoraPk AS ID')
            ->addSelect('a.nombre AS NOMBRE')
            ->addSelect('a.numeroIdentificacion AS NIT')
            ->addSelect('a.digitoVerificacion AS DIGITO');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
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

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteAseguradora::class, 'a')
            ->select('a.codigoAseguradoraPk')
            ->addSelect('a.numeroIdentificacion')
            ->addSelect('a.digitoVerificacion')
            ->addSelect('a.nombre');

        if ($codigo) {
            $queryBuilder->andWhere("a.codigoAseguradoraPk = '{$codigo}'");
        }

        if($nombre){
            $queryBuilder->andWhere("a.nombre LIKE '%{$nombre}%'");
        }


        $queryBuilder->addOrderBy('a.codigoAseguradoraPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function eliminar($arrSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $em->getRepository(TteAseguradora::class)->find($codigo);
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