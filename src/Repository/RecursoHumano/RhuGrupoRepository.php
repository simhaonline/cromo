<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuGrupo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuGrupoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuGrupo::class);
    }

	public function lista($raw)
	{
		$limiteRegistros = $raw['limiteRegistros'] ?? 100;
		$filtros = $raw['filtros'] ?? null;
		$codigoGrupo = null;
		$nombre = null;
		if ($filtros) {
			$codigoGrupo = $filtros['codigoGrupo'] ?? null;
			$nombre = $filtros['nombre'] ?? null;
		}

		$queryBuilder = $this->getEntityManager ()->createQueryBuilder ()->from (RhuGrupo::class, 'g')
			->select ('g.codigoGrupoPk')
			->addSelect ('g.nombre')
			->addSelect ('g.cargarContrato')
			->addSelect ('g.cargarSoporte')
            ->addSelect('g.generaPedido')
			->addSelect ('g.codigoDistribucionFk');
		if ($codigoGrupo) {
			$queryBuilder->andWhere ("g.codigoGrupoPk = '{$codigoGrupo}'");
		}
		if ($nombre) {
			$queryBuilder->andWhere ("g.nombre LIKE '%{$nombre}%'");
		}
		$queryBuilder->addOrderBy('g.codigoGrupoPk', 'ASC');
		$queryBuilder->setMaxResults($limiteRegistros);
		return $queryBuilder->getQuery()->getResult();
	}

	/**
	 * @param $arrSeleccionados
	 * @throws \Doctrine\ORM\ORMException
	 */
	public function eliminar($arrSeleccionados)
	{
		foreach ($arrSeleccionados as $arrSeleccionado) {
			$ar = $this->getEntityManager()->getRepository(RhuGrupo::class)->find($arrSeleccionado);
			if ($ar) {
				$this->getEntityManager()->remove($ar);
			}
		}
		$this->getEntityManager()->flush();
	}

    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from(RhuGrupo::class,'gp')
            ->select('gp.codigoGrupoPk AS ID')
            ->addSelect('gp.nombre AS NOMBRE');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function llenarCombo()
    {
        $session = new Session();
        $array = [
            'class' => RhuGrupo::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('g')
                    ->orderBy('g.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        ];
        if ($session->get('filtroRhuGrupo')) {
            $array['data'] = $this->getEntityManager()->getReference(RhuGrupo::class, $session->get('filtroRhuGrupo'));
        }
        return $array;
    }
}