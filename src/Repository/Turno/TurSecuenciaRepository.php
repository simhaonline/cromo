<?php

namespace App\Repository\Turno;

use App\Entity\Turno\TurSecuencia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TurSecuenciaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TurSecuencia::class);
    }

    public function lista(){
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurSecuencia::class, 's')
            ->select('s.codigoSecuenciaPk')
            ->addSelect('s.nombre')
            ->addSelect('s.dias')
            ->addSelect('s.dia1')
            ->addSelect('s.dia2')
            ->addSelect('s.dia3')
            ->addSelect('s.dia4')
            ->addSelect('s.dia5')
            ->addSelect('s.dia6')
            ->addSelect('s.dia7')
            ->addSelect('s.dia8')
            ->addSelect('s.dia9')
            ->addSelect('s.dia10')
            ->addSelect('s.dia11')
            ->addSelect('s.dia12')
            ->addSelect('s.dia13')
            ->addSelect('s.dia14')
            ->addSelect('s.dia15')
            ->addSelect('s.dia16')
            ->addSelect('s.dia17')
            ->addSelect('s.dia18')
            ->addSelect('s.dia19')
            ->addSelect('s.dia20')
            ->addSelect('s.dia21')
            ->addSelect('s.dia22')
            ->addSelect('s.dia23')
            ->addSelect('s.dia24')
            ->addSelect('s.dia25')
            ->addSelect('s.dia26')
            ->addSelect('s.dia26')
            ->addSelect('s.dia27')
            ->addSelect('s.dia28')
            ->addSelect('s.dia29')
            ->addSelect('s.dia30')
            ->addSelect('s.dia31')
            ->addSelect('s.lunes')
            ->addSelect('s.martes')
            ->addSelect('s.miercoles')
            ->addSelect('s.jueves')
            ->addSelect('s.viernes')
            ->addSelect('s.sabado')
            ->addSelect('s.domingo')
            ->addSelect('s.festivo')
            ->addSelect('s.domingoFestivo');
        if ($session->get('filtroTurSecuenciaCodigoSecuencia') != '') {
            $queryBuilder->andWhere("s.codigoSecuenciaPk LIKE '%{$session->get('filtroTurSecuenciaCodigoSecuencia')}%'");

        }
        if ($session->get('filtroTurSecuenciaNombre') != '') {
            $queryBuilder->andWhere("s.nombre LIKE '%{$session->get('filtroTurSecuenciaNombre')}%'");
        }
        return $queryBuilder->getQuery()->getResult();
    }

}
