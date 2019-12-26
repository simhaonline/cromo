<?php

namespace App\Repository\RecursoHumano;

use App\Entity\Financiero\FinCuenta;
use App\Entity\RecursoHumano\RhuConfiguracionProvision;
use App\Entity\RecursoHumano\RhuProgramacionDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuConfiguracionProvisionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuConfiguracionProvision::class);
    }

    public function lista()
    {

        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuConfiguracionProvision::class, 'cp')
            ->select('cp.codigoConfiguracionProvisionPk')
            ->addSelect('cp.tipo')
            ->addSelect('cp.nombre')
            ->addSelect('cp.codigoCostoClaseFk')
            ->addSelect('cp.codigoCuentaDebitoFk')
            ->addSelect('cp.codigoCuentaCreditoFk');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param $arrCuentas RhuConfiguracionProvision
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function actualizarCuentas($arrCuentas)
    {
        $error = false;
        $em = $this->getEntityManager();
        foreach ($arrCuentas as $clave => $valor) {
            if ($arrCuentas) {
                if ($clave != "form") {
                    $arConfiguracionProvision = $em->getRepository(RhuConfiguracionProvision::class)->find($clave);
                    $cuentaDebito = $em->getRepository(FinCuenta::class)->find($valor['cuentaDebito']);
                    if ($cuentaDebito) {
                        $arConfiguracionProvision->setCodigoCuentaDebitoFk($cuentaDebito->getCodigoCuentaPk());
                    } else {
                        $arConfiguracionProvision->setCodigoCuentaDebitoFk(null);
                        $error = true;
                    }
                    $cuentaCredito = $em->getRepository(FinCuenta::class)->find($valor['cuentaCredito']);
                    if ($cuentaCredito) {
                        $arConfiguracionProvision->setCodigoCuentaCreditoFk($cuentaCredito->getCodigoCuentaPk());
                    } else {
                        $arConfiguracionProvision->setCodigoCuentaCreditoFk(null);
                        $error = true;
                    }
                    $em->persist($arConfiguracionProvision);
                }
            }
        }
        $em->flush();
        return $error;
    }

}