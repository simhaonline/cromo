<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteDespachoTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TteDespachoTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteDespachoTipo::class);
    }

    public function camposPredeterminados()
    {
        $qb = $this->_em->createQueryBuilder()
            ->from('App:Transporte\TteDespachoTipo', 'dt')
            ->select('dt.codigoDespachoTipoPk AS ID')
            ->addSelect('dt.nombre AS NOMBRE')
            ->addSelect('dt.consecutivo AS CONSECUTIVO')
            ->addSelect('dt.exigeNumero AS EXIGE_NUMERO')
            ->addSelect('dt.generaMonitoreo AS GENERA_MONITOREO')
            ->addSelect('dt.viaje AS VIAJE')
            ->addSelect('dt.codigoComprobanteFk AS COMPROBANTE_FK');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

    public function llenarCombo()
    {
        $session = new Session();
        $array = [
            'class' => TteDespachoTipo::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('r')
                    ->orderBy('r.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        ];
        if ($session->get('filtroTteDespachoTipo')) {
            $array['data'] = $this->getEntityManager()->getReference(TteDespachoTipo::class, $session->get('filtroTteDespachoTipo'));
        }
        return $array;
    }

}