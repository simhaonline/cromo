<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuRequisito;
use App\Entity\RecursoHumano\RhuRequisitoCargo;
use App\Entity\RecursoHumano\RhuRequisitoDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuRequisitoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuRequisito::class);
    }


    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuRequisito::class, 'r')
            ->select('r.codigoRequisitoPk')
            ->addSelect('rt.nombre AS nombreRequisito')
            ->addSelect('c.nombre AS nombreCargo')
            ->addSelect('r.nombreCorto')
            ->addSelect('r.numeroIdentificacion')
            ->leftJoin('r.requisitoTipoRel','rt')
            ->leftJoin('rhrc.cargoRel','c');



//        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuRequisitoCargo::class, 'rhrc')
//            ->select('rhrc.codigoRequisitoCargoPk')
//            ->addSelect('rc.nombre AS nombreRequisito')
//            ->addSelect('c.nombre AS nombreCargo')
//            ->addSelect('rc.general')
//            ->leftJoin('rhrc.requisitoConceptoRel','rc')
//            ->leftJoin('rhrc.cargoRel','c')
//            ->orderBy('rhrc.codigoRequisitoCargoPk', 'ASC');


            if ($session->get('RhuRequisitoCargo_numeroIdentificacion')) {
                $queryBuilder->andWhere("r.numeroIdentificacion = '{$session->get('RhuRequisitoCargo_numeroIdentificacion')}'");
            }

            if ($session->get('RhuRequisitoCargo_nombreCorto')) {
                $queryBuilder->andWhere("r.nombreCorto = '{$session->get('RhuRequisitoCargo_nombreCorto')}'");
            }
        return $queryBuilder;
    }

    /**
     * @param $arrSeleccionados
     * @return string
     * @throws \Doctrine\ORM\ORMException
     */
    public function eliminar($arrSeleccionados)
    {
        $respuesta = '';
        $em = $this->getEntityManager();
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository(RhuRequisitoDetalle::class)->find($codigo);
                if ($ar) {
                    $em->remove($ar);
                }
            }
            try {
                $em->flush();
            } catch (\Exception $exception) {
                $respuesta = 'No se puede eliminar, el registro esta siendo utilizado en el sistema';
            }
        }
        return $respuesta;
    }
}