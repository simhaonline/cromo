<?php

namespace App\Repository\Turno;


use App\Entity\Turno\TurCliente;
use App\Entity\Turno\TurPrototipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TurPrototipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TurPrototipo::class);
    }

    public function listaProgramar($codigoContratoDetalle){
        $session = new Session();
        $arPrototipos = null;
        if($codigoContratoDetalle) {
            $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurPrototipo::class, 'p')
                ->select('p.codigoPrototipoPk')
                ->where("p.codigoContratoDetalleFk = {$codigoContratoDetalle}");
            $arPrototipos = $queryBuilder->getQuery()->getResult();
        }
        return $arPrototipos;
    }

}
