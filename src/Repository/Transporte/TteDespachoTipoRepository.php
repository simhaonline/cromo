<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteDespachoTipo;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TteDespachoTipoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
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

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigo = null;
        $nombre = null;

        if ($filtros) {
            $codigo = $filtros['codigo'] ?? null;
            $nombre = $filtros['nombre'] ?? null;
        }


        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteDespachoTipo::class, 'dt')
            ->select('dt.codigoDespachoTipoPk')
            ->addSelect('dt.nombre')
            ->addSelect('dt.consecutivo')
            ->addSelect('dt.viaje')
            ->addSelect('dt.generaCuentaPagar')
            ->addSelect('dt.codigoCuentaPagarTipoFk')
            ->addSelect('dt.codigoCuentaPagarTipoAnticipoFk')
            ->addSelect('dt.codigoComprobanteFk')
            ->addSelect('dt.codigoCuentaFleteFk')
            ->addSelect('dt.codigoCuentaRetencionFuenteFk')
            ->addSelect('dt.codigoCuentaIndustriaComercioFk')
            ->addSelect('dt.codigoCuentaSeguridadFk')
            ->addSelect('dt.codigoCuentaCargueFk')
            ->addSelect('dt.codigoCuentaEstampillaFk')
            ->addSelect('dt.codigoCuentaPapeleriaFk')
            ->addSelect('dt.codigoCuentaAnticipoFk')
            ->addSelect('dt.codigoCuentaPagarFk')
            ->addSelect('dt.codigoDespachoClaseFk');

        if ($codigo) {
            $queryBuilder->andWhere("dt.codigoDespachoTipoPk = '{$codigo}'");
        }

        if($nombre){
            $queryBuilder->andWhere("dt.nombre LIKE '%{$nombre}%'");
        }


        $queryBuilder->addOrderBy('dt.codigoDespachoTipoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function eliminar($arrSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $em->getRepository(TteDespachoTipo::class)->find($codigo);
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