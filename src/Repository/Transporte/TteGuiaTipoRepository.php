<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteGuiaTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TteGuiaTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteGuiaTipo::class);
    }

    public function camposPredeterminados()
    {
        $qb = $this->_em->createQueryBuilder()
            ->from('App:Transporte\TteGuiaTipo', 'gt')
            ->select('gt.codigoGuiaTipoPk AS ID')
            ->addSelect('gt.nombre AS NOMBRE')
            ->addSelect('gt.consecutivo AS CONSECUTIVO')
            ->addSelect('gt.exigeNumero AS EXIGE_NUMERO');
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
            'class' => TteGuiaTipo::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('gt')
                    ->orderBy('gt.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        ];
        if ($session->get('filtroTteGuiaCodigoGuiaTipo')) {
            $array['data'] = $this->getEntityManager()->getReference(TteGuiaTipo::class, $session->get('filtroTteGuiaCodigoGuiaTipo'));
        }
        return $array;
    }


    public function apiWindowsLista($raw) {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(TteGuiaTipo::class, 'gt')
            ->select('gt.codigoGuiaTipoPk')
            ->addSelect('gt.nombre')
            ->orderBy('gt.orden');
        $arGuiaTipo = $queryBuilder->getQuery()->getResult();
        return $arGuiaTipo;
    }

    public function apiWindowsDetalle($raw) {
        $em = $this->getEntityManager();
        $guiaTipo = $raw['guiaTipo']?? null;
        if($guiaTipo) {
            $arGuiaTipo = $em->getRepository(TteGuiaTipo::class)->find($guiaTipo);
            if($arGuiaTipo) {
                return [
                    "nombre" => $arGuiaTipo->getNombre(),
                    "exigeNumero" => $arGuiaTipo->getExigeNumero(),
                    "validarFlete" => $arGuiaTipo->getValidarFlete(),
                    "factura" => $arGuiaTipo->getFactura(),
                    "cortesia" => $arGuiaTipo->getCortesia(),
                    "codigoFormaPago" => $arGuiaTipo->getCodigoFormaPago()
                ];
            } else {
                return ["error" => "Usuario o clave invalidos"];
            }
        } else {
            return ["error" => "Faltan datos para la api"];
        }
    }

}