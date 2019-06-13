<?php

namespace App\Repository\Cartera;

use App\Entity\Cartera\CarCuentaCobrarTipo;
use App\Entity\Transporte\TteGuiaTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class CarCuentaCobrarTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CarCuentaCobrarTipo::class);
    }

    public function lista(): array
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder()->from(CarCuentaCobrarTipo::class, 'cct');
        $qb->select('cct.codigoCuentaCobrarTipoPk')
            ->addSelect('cct.nombre')
            ->addSelect('cct.codigoCuentaClienteFk')
            ->addSelect('cct.codigoCuentaRetencionFuenteFk')
            ->addSelect('cct.codigoCuentaRetencionIcaFk')
            ->addSelect('cct.codigoCuentaRetencionIvaFk')
            ->addSelect('cct.tipoCuentaCliente')
            ->where('cct.codigoCuentaCobrarTipoPk <> 0')
            ->orderBy('cct.codigoCuentaCobrarTipoPk', 'DESC');
        $query = $qb->getDQL();
        $query = $em->createQuery($query);

        return $query->execute();
    }

    public function eliminar($arrSeleccionados)
    {
        $respuesta = '';
        $em = $this->getEntityManager();
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository(CarCuentaCobrarTipo::class)->find($codigo);
                if ($ar) {
                    $em->remove($ar);
                }
            }
            try {
                $em->flush();
            } catch (\Exception $exception) {
                $respuesta = 'No se puede eliminar, el registro esta siendo utilizado en el sistema';
            }
        }
        return $respuesta;
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function llenarCombo()
    {
        $session = new Session();
        $array = [
            'class' => CarCuentaCobrarTipo::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        ];
        if ($session->get('filtroCarCuentaCobrarTipo')) {
            $array['data'] = $this->getEntityManager()->getReference(CarCuentaCobrarTipo::class, $session->get('filtroCarCuentaCobrarTipo'));
        }
        return $array;
    }
    /*
    * se creo esta función para cumplir las necesidad del caso 30
     * att andres
    */
    public function selectCodigoNombre()
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder()->from(CarCuentaCobrarTipo::class, 'cct');
        $qb->select('cct.codigoCuentaCobrarTipoPk')
            ->addSelect('cct.nombre');
        return $qb->getQuery()->getResult();
    }
}