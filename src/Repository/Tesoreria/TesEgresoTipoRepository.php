<?php

namespace App\Repository\Tesoreria;

use App\Entity\Compra\ComEgresoTipo;
use App\Entity\Tesoreria\TesEgresoTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @method ComEgresoTipo|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComEgresoTipo|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComEgresoTipo[]    findAll()
 * @method ComEgresoTipo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TesEgresoTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TesEgresoTipo::class);
    }


    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function llenarCombo()
    {
        $session = new Session();
        $array = [
            'class' => ComEgresoTipo::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('et')
                    ->orderBy('et.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        ];
        if ($session->get('filtroComEgresoTipo')) {
            $array['data'] = $this->getEntityManager()->getReference(ComEgresoTipo::class, $session->get('filtroComEgresoTipo'));
        }
        return $array;
    }

    public function camposPredeterminados()
    {
        $queryBuilder = $this->_em->createQueryBuilder()->from(ComEgresoTipo::class, 'et')
            ->select('et.codigoEgresoTipoPk as ID')
            ->addSelect('et.nombre')
            ->where('et.codigoEgresoTipoPk IS NOT NULL');
        return $queryBuilder->getQuery()->execute();
    }
}
