<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuEmbargo;
use App\Entity\RecursoHumano\RhuRecaudo;
use App\Entity\RecursoHumano\RhuReclamo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuRecaudoRepository extends ServiceEntityRepository
{

    /**
     * @return string
     */
    public function getRuta(){
        return 'recursohumano_movimiento_reclamo_reclamo_';
    }

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuRecaudo::class);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function lista()
    {
        $session = new Session();

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuRecaudo::class, 'r')
            ->select('r.codigoRecaudoPk')
            ->addSelect('r.numero')
            ->addSelect('r.fecha')
            ->addSelect('r.codigoEntidadFk')
            ->addSelect('r.fechaPago')
            ->addSelect('r.vrTotal')
            ->addSelect('r.estadoAutorizado')
            ->addSelect('r.estadoAprobado')
            ->addSelect('r.estadoAnulado')
            ->addSelect('r.comentarios');

        if ($session->get('RhuRecaudo_codigoRecaudoPk')) {
            $queryBuilder->andWhere("r.codigoRecaudoPk = '{$session->get('RhuRecaudo_codigoRecaudoPk')}'");
        }

        if ($session->get('RhuRecaudo_codigoEntidadFk')) {
            $queryBuilder->andWhere("r.codigoEntidadFk = '{$session->get('RhuRecaudo_codigoEntidadFk')}'");
        }

        if ($session->get('RhuRecaudo_numero')) {
            $queryBuilder->andWhere("r.numero = '{$session->get('RhuRecaudo_numero')}'");
        }

        if ($session->get('RhuRecaudo_fechaPagoDesde') != null) {
            $queryBuilder->andWhere("r.fechaPagoDesde >= '{$session->get('RhuRecaudo_fechaPagoDesde')} 00:00:00'");
        }

        if ($session->get('RhuRecaudo_fechaPagoHasta') != null) {
            $queryBuilder->andWhere("r.fechaPagoHasta <= '{$session->get('RhuRecaudo_fechaPagoHasta')} 23:59:59'");
        }

        switch ($session->get('RhuRecaudo_estadoAutorizado')) {
            case '0':
                $queryBuilder->andWhere("r.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("r.estadoAutorizado = 1");
                break;
        }

        switch ($session->get('RhuRecaudo_estadoAprobado')) {
            case '0':
                $queryBuilder->andWhere("r.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("r.estadoAprobado = 1");
                break;
        }

        switch ($session->get('RhuRecaudo_estadoAnulado')) {
            case '0':
                $queryBuilder->andWhere("r.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("r.estadoAnulado = 1");
                break;
        }
        
        return $queryBuilder;
    }

    /**
     * @return array
     */
    public function parametrosLista(){
        $arEmbargo = new RhuEmbargo();
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuEmbargo::class,'re')
            ->select('re.codigoEmbargoPk')
            ->addSelect('re.fecha')
            ->where('re.codigoEmbargoPk <> 0');
        $arrOpciones = ['json' =>'[{"campo":"codigoEmbargoPk","ayuda":"Codigo del embargo","titulo":"ID"},
        {"campo":"fecha","ayuda":"Fecha de registro","titulo":"FECHA"}]',
            'query' => $queryBuilder,'ruta' => $this->getRuta()];
        return $arrOpciones;
    }

    /**
     * @return mixed
     */
    public function parametrosExcel(){
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuEmbargo::class,'re')
            ->select('re.codigoEmbargoPk')
            ->addSelect('re.fecha')
            ->where('re.codigoEmbargoPk <> 0');
        return $queryBuilder->getQuery()->execute();
    }


}