<?php

namespace App\Repository\Compra;

use App\Entity\Compra\ComCuentaPagarTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @method ComCuentaPagarTipo|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComCuentaPagarTipo|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComCuentaPagarTipo[]    findAll()
 * @method ComCuentaPagarTipo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComCuentaPagarTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ComCuentaPagarTipo::class);
    }

    public function camposPredeterminados()
    {
        $queryBuilder = $this->_em->createQueryBuilder()->from(ComCuentaPagarTipo::class, 'cpt')
            ->select('cpt.codigoCuentaPagarTipoPk as ID')
            ->addSelect('cpt.nombre')
            ->where('cpt.codigoCuentaPagarTipoPk IS NOT NULL');
        return $queryBuilder->getQuery()->execute();
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function llenarCombo()
    {
        $session = new Session();
        $array = [
            'class' => ComCuentaPagarTipo::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('cp')
                    ->orderBy('cp.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        ];
        if ($session->get('filtroComCuentaPagarTipo')) {
            $array['data'] = $this->getEntityManager()->getReference(ComCuentaPagarTipo::class, $session->get('filtroComCuentaPagarTipo'));
        }
        return $array;
    }
}
