<?php


namespace App\Repository\RecursoHumano;


use App\Entity\RecursoHumano\RhuIncapacidad;
use App\Entity\RecursoHumano\RhuIncapacidadDiagnostico;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuIncapacidadDiagnosticoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuIncapacidadDiagnostico::class);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuIncapacidadDiagnostico::class, 'id');
        $queryBuilder
            ->select('id.codigoIncapacidadDiagnosticoPk')
            ->addSelect('id.nombre')
        ->addSelect('id.codigo');
        if($session->get('RhuIncapacidadDiagnosticoNombre') != ""){
            $queryBuilder->andWhere("id.nombre LIKE '%{$session->get('RhuIncapacidadDiagnosticoNombre')}%'");
        }
        if($session->get('RhuIncapacidadDiagnosticoCodigo') != ""){
            $queryBuilder->andWhere("id.codigoIncapacidadDiagnosticoPk = '{$session->get('RhuIncapacidadDiagnosticoCodigo')}' ");
        }
        if($session->get('RhuIncapacidadDiagnosticoCodigoEps') != ""){
            $queryBuilder->andWhere("id.codigo LIKE '%{$session->get('RhuIncapacidadDiagnosticoCodigoEps')}%'");
        }
        return $queryBuilder;
    }
}