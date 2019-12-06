<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuContratoTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityRepository;
class RhuContratoTipoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuContratoTipo::class);
    }

    public function camposPredeterminados(){
        return $this->_em->createQueryBuilder()->from(RhuContratoTipo::class,'ct')
            ->select('ct.codigoContratoTipoPk AS ID')
            ->addSelect('ct.nombre')
            ->where('ct.codigoContratoTipoPk IS NOT NULL');
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function llenarCombo()
    {
        $session = new Session();
        $array = [
            'class' => RhuContratoTipo::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('ct')
                    ->orderBy('ct.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        ];
        if ($session->get('filtroRhuContratoTipo')) {
            $array['data'] = $this->getEntityManager()->getReference(RhuContratoTipo::class, $session->get('filtroRhuContratoTipo'));
        }
        return $array;
    }

}