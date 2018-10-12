<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEmpleado;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuContratoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuContrato::class);
    }

    /**
     * @return string
     */
    public function getRuta()
    {
        return 'recursohumano_administracion_contrato_contrato_';
    }

    public function camposPredeterminados()
    {
        $qb = $this->_em->createQueryBuilder()
            ->from('App:RecursoHumano\RHuEmpleado', 'e')
            ->select('e.codigoEmpleadoPk AS ID');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

    public function contratosEmpleado($codigoEmpleado){
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuContrato::class, 're')
            ->select('re.codigoContratoPk')
            ->addSelect('re.fechaDesde')
            ->addSelect('re.fechaHasta')
            ->addSelect('re.vrSalario')
            ->addSelect('re.estadoActivo')
            ->where('re.codigoEmpleadoFk = '.$codigoEmpleado)
            ->andWhere('re.codigoContratoPk <> 0');
        return $queryBuilder->getQuery()->execute();
    }

    /**
     * @return array
     */
    public function parametrosLista()
    {
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuContrato::class, 're')
            ->select('re.codigoContratoPk')
            ->addSelect('re.fechaDesde')
            ->addSelect('re.fechaHasta')
            ->addSelect('re.vrSalario')
            ->addSelect('re.estadoActivo')
            ->where('re.codigoContratoPk <> 0');
        $arrOpciones = ['json' => '[
        {"campo":"codigoContratoPk","ayuda":"Codigo del contrato","titulo":"ID"},
        {"campo":"fechaDesde","ayuda":"Fecha de inicio del contrato","titulo":"DESDE"},
        {"campo":"fechaHasta","ayuda":"Fecha de finalizacion del contrato","titulo":"HASTA"},
        {"campo":"vrSalario","ayuda":"Valor del salario","titulo":"SALARIO"},
        {"campo":"estadoActivo","ayuda":"Estado del contrato","titulo":"CONTRATO"}]',
            'query' => $queryBuilder, 'ruta' => $this->getRuta()];
        return $arrOpciones;
    }

    /**
     * @return mixed
     */
    public function parametrosExcel()
    {
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuContrato::class, 're')
            ->select('re.codigoContratoPk')
            ->addSelect('re.fechaDesde')
            ->addSelect('re.fechaHasta')
            ->addSelect('re.vrSalario')
            ->addSelect('re.estadoActivo')
            ->where('re.codigoContratoPk <> 0');
        return $queryBuilder->getQuery()->execute();
    }
}