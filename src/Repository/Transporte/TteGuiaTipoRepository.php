<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteGuiaTipo;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TteGuiaTipoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
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
                    "codigoGuiaTipoPk" => $arGuiaTipo->getCodigoGuiaTipoPk(),
                    "nombre" => $arGuiaTipo->getNombre(),
                    "exigeNumero" => $arGuiaTipo->getExigeNumero(),
                    "validarFlete" => $arGuiaTipo->getValidarFlete(),
                    "factura" => $arGuiaTipo->getFactura(),
                    "cortesia" => $arGuiaTipo->getCortesia(),
                    "codigoFormaPago" => $arGuiaTipo->getCodigoFormaPago(),
                    "generaCobro" => $arGuiaTipo->getGeneraCobro()
                ];
            } else {
                return ["error" => "Usuario o clave invalidos"];
            }
        } else {
            return ["error" => "Faltan datos para la api"];
        }
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigo = null;
        $nombre = null;
        $incidenteTipo = null;
        $fechaDesde = null;
        $fechaHasta = null;
        $estadoAutorizado = null;
        $estadoAprobado = null;
        $estadoAnulado = null;

        if ($filtros) {
            $codigo = $filtros['codigo'] ?? null;
            $nombre = $filtros['nombre'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteGuiaTipo::class, 'gt')
            ->select('gt.codigoGuiaTipoPk')
            ->addSelect('gt.nombre')
            ->addSelect('gt.factura')
            ->addSelect('gt.consecutivo')
            ->addSelect('gt.codigoFacturaTipoFk')
            ->addSelect('gt.exigeNumero')
            ->addSelect('gt.orden')
            ->addSelect('gt.validarFlete')
            ->addSelect('gt.validarRango')
            ->addSelect('gt.cortesia');

        if ($codigo) {
            $queryBuilder->andWhere("gt.codigoGuiaTipoPk = '{$codigo}'");
        }

        if($nombre){
            $queryBuilder->andWhere("gt.nombre LIKE '%{$nombre}%'");
        }

        $queryBuilder->addOrderBy('gt.codigoGuiaTipoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function eliminar($arrSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $em->getRepository(TteGuiaTipo::class)->find($codigo);
                if ($arRegistro) {
                    $em->remove($arRegistro);
                }
            }
            try {
                $em->flush();
            } catch (\Exception $e) {
                Mensajes::error('No se puede eliminar, el registro se encuentra en uso en el sistema');
            }
        }else{
            Mensajes::error("No existen registros para eliminar");
        }
    }

}