<?php

namespace App\Repository\Turno;


use App\Entity\Crm\CrmVisita;
use App\Entity\Turno\TurConcepto;
use App\Entity\Turno\TurContratoDetalle;
use App\Entity\Turno\TurFestivo;
use App\Entity\Turno\TurPedido;
use App\Entity\Turno\TurPedidoDetalle;
use App\Entity\Turno\TurProgramacion;
use App\Entity\Turno\TurTurno;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TurProgramacionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TurProgramacion::class);
    }

    public function detalleProgramacion(){
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurPedidoDetalle::class, 'pd')
            ->select('pd.codigoPedidoDetallePk');
        $arrPedidoDetalles = $queryBuilder->getQuery()->getResult();
        $c = 0;
        foreach ($arrPedidoDetalles as $arrPedidoDetalle) {
            $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurProgramacion::class, 'p')
                ->select('p.codigoProgramacionPk')
                ->addSelect('p.horasDiurnas')
                ->addSelect('p.horasNocturnas')
                ->where('p.codigoPedidoDetalleFk = ' . $arrPedidoDetalle['codigoPedidoDetallePk']);
            for ($i = 1;$i<=31;$i++) {
                $queryBuilder->addSelect("p.dia{$i}");
            }
            $arProgramaciones = $queryBuilder->getQuery()->getResult();
            $arrPedidoDetalles[$c]['arProgramaciones'] = $arProgramaciones;
            $c++;
        }

        return $arrPedidoDetalles;
    }
}
