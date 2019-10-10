<?php

namespace App\Repository\Inventario;


use App\Entity\Cartera\CarCliente;
use App\Entity\Financiero\FinTercero;
use App\Entity\Inventario\InvTercero;
use App\Entity\Tesoreria\TesTercero;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class InvTerceroRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvTercero::class);
    }

    public function camposPredeterminados()
    {
        $qb = $this->getEntityManager()->createQueryBuilder()->from('App:Inventario\InvTercero', 'it');
        $qb
            ->select('it.codigoTerceroPk AS ID')
            ->addSelect('it.apellido1 AS APELLIDO1')
            ->addSelect('it.apellido2 AS APELLIDO2')
            ->addSelect('it.nombres AS NOMBRES')
            ->addSelect('it.numeroIdentificacion AS IDENTIFICACION')
            ->addSelect('it.direccion AS DIRECCION')
            ->where("it.codigoTerceroPk <> 0")
            ->orderBy('it.codigoTerceroPk', 'DESC');
        $dql = $this->getEntityManager()->createQuery($qb->getDQL());
        return $dql->execute();
    }

    /**
     * Esta función se crea con la idea de separar la lista del controlador de la lista del buscar avanzado
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function listaControlador($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoTercero = null;
        $nombreTercero = null;

        if ($filtros) {
            $codigoTercero = $filtros['codigoTercero'] ?? null;
            $nombreTercero = $filtros['nombreTercero'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvTercero::class, 't')
            ->select('t.codigoTerceroPk')
            ->addSelect('t.codigoIdentificacionFk')
            ->addSelect('t.numeroIdentificacion')
            ->addSelect('t.digitoVerificacion')
            ->addSelect('t.nombreCorto')
            ->addSelect('t.telefono')
            ->addSelect('t.direccion')
            ->addSelect('c.nombre AS ciudadNombre')
            ->addSelect('t.cliente')
            ->addSelect('t.proveedor')
            ->addSelect('t.retencionFuente')
            ->addSelect('t.retencionFuenteSinBase')
            ->leftJoin('t.ciudadRel', 'c')
            ->where('t.codigoTerceroPk <> 0');
        if ($codigoTercero) {
            $queryBuilder->andWhere("t.codigoTerceroPk = {$codigoTercero}");
        }
        if ($nombreTercero) {
            $queryBuilder->andWhere("t.nombreCorto LIKE '%{$nombreTercero}%'");
        }
        $queryBuilder->addOrderBy('t.codigoTerceroPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder;

    }

    public function lista($terceroTipo)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvTercero::class, 't')
            ->select('t.codigoTerceroPk')
            ->addSelect('t.codigoIdentificacionFk')
            ->addSelect('t.numeroIdentificacion')
            ->addSelect('t.digitoVerificacion')
            ->addSelect('t.nombreCorto')
            ->addSelect('t.telefono')
            ->addSelect('t.direccion')
            ->addSelect('c.nombre AS ciudadNombre')
            ->addSelect('t.cliente')
            ->addSelect('t.proveedor')
            ->addSelect('t.retencionFuente')
            ->addSelect('t.retencionFuenteSinBase')
            ->leftJoin('t.ciudadRel', 'c')
            ->where('t.codigoTerceroPk <> 0');
        if ($session->get('filtroInvTerceroCodigo') != '') {
            $queryBuilder->andWhere("t.codigoTerceroPk = {$session->get('filtroInvTerceroCodigo')}");
        }
        if ($session->get('filtroInvTerceroNombre') != '') {
            $queryBuilder->andWhere("t.nombreCorto LIKE '%{$session->get('filtroInvTerceroNombre')}%'");
        }
        if ($session->get('filtroInvNombreTercero') != '') {
            $queryBuilder->andWhere("t.nombreCorto LIKE '%{$session->get('filtroInvNombreTercero')}%'");
        }
        if ($session->get('filtroInvTerceroIdentificacion') != '') {
            $queryBuilder->andWhere("t.numeroIdentificacion = {$session->get('filtroInvTerceroIdentificacion')}");
        }

        switch ($terceroTipo) {
            case 'C':
                $queryBuilder->andWhere("t.cliente = 1");
                break;

            case 'P':
                $queryBuilder->andWhere("t.proveedor = 1");
                break;
        }
        return $queryBuilder;
    }

    public function terceroFinanciero($codigo)
    {
        $em = $this->getEntityManager();
        $arTercero = null;
        $arTerceroInventario = $em->getRepository(InvTercero::class)->find($codigo);
        if ($arTerceroInventario) {
            $arTercero = $em->getRepository(FinTercero::class)->findOneBy(array('codigoIdentificacionFk' => $arTerceroInventario->getCodigoIdentificacionFk(), 'numeroIdentificacion' => $arTerceroInventario->getNumeroIdentificacion()));
            if (!$arTercero) {
                $arTercero = new FinTercero();
                $arTercero->setIdentificacionRel($arTerceroInventario->getIdentificacionRel());
                $arTercero->setNumeroIdentificacion($arTerceroInventario->getNumeroIdentificacion());
                $arTercero->setNombreCorto($arTerceroInventario->getNombreCorto());
                $arTercero->setDireccion($arTerceroInventario->getDireccion());
                $arTercero->setTelefono($arTercero->getTelefono());
                $em->persist($arTercero);
            }
        }

        return $arTercero;
    }

    public function terceroCartera($codigo)
    {
        $em = $this->getEntityManager();
        $arCliente = null;
        $arTerceroInventario = $em->getRepository(InvTercero::class)->find($codigo);
        if ($arTerceroInventario) {
            $arCliente = $em->getRepository(CarCliente::class)->findOneBy(array('codigoIdentificacionFk' => $arTerceroInventario->getCodigoIdentificacionFk(), 'numeroIdentificacion' => $arTerceroInventario->getNumeroIdentificacion()));
            if (!$arCliente) {
                $arCliente = new CarCliente();
                $arCliente->setIdentificacionRel($arTerceroInventario->getIdentificacionRel());
                $arCliente->setNumeroIdentificacion($arTerceroInventario->getNumeroIdentificacion());
                $arCliente->setNombreCorto($arTerceroInventario->getNombreCorto());
                $arCliente->setDireccion($arTerceroInventario->getDireccion());
                $arCliente->setTelefono($arCliente->getTelefono());
                $arCliente->setFormaPagoRel($arTerceroInventario->getFormaPagoRel());
                $arCliente->setCiudadRel($arTerceroInventario->getCiudadRel());
                $em->persist($arCliente);
                $em->flush();
            }
        }

        return $arCliente;
    }

    public function informeBloqueados()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvTercero::class, 't')
            ->select("t")
            ->where('t.bloqueoCartera = true');
        if ($session->get('filtroInvTerceroInformeCodigoTercero')) {
            $queryBuilder->andWhere("t.codigoTerceroPk = {$session->get('filtroInvTerceroInformeCodigoTercero')}");
        }

        return $queryBuilder->getQuery();
    }

    public function informeBloqueadosExcel()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvTercero::class, 't')
            ->select("t.codigoTerceroPk")
            ->addSelect('i.nombre AS tipoIdentificacion')
            ->addSelect('t.numeroIdentificacion')
            ->addSelect('c.nombre AS ciudad')
            ->addSelect('t.nombreCorto AS tercero')
            ->leftJoin('t.identificacionRel', 'i')
            ->leftJoin('t.ciudadRel', 'c')
            ->where('t.bloqueoCartera = true');
        if ($session->get('filtroInvTerceroInformeCodigoTercero') != '') {
            $queryBuilder->andWhere("t.codigoTerceroPk = {$session->get('filtroInvTerceroInformeCodigoTercero')}");
        }

        return $queryBuilder;
    }

    public function terceroTesoreria($arTerceroInventario)
    {
        $em = $this->getEntityManager();
        $arTercero = null;
        if ($arTerceroInventario) {
            $arTercero = $em->getRepository(TesTercero::class)->findOneBy(array('codigoIdentificacionFk' => $arTerceroInventario->getCodigoIdentificacionFk(), 'numeroIdentificacion' => $arTerceroInventario->getNumeroIdentificacion()));
            if (!$arTercero) {
                $arTercero = new TesTercero();
                $arTercero->setIdentificacionRel($arTerceroInventario->getIdentificacionRel());
                $arTercero->setNumeroIdentificacion($arTerceroInventario->getNumeroIdentificacion());
                $arTercero->setNombreCorto($arTerceroInventario->getNombreCorto());
                $arTercero->setDireccion($arTerceroInventario->getDireccion());
                $arTercero->setTelefono($arTerceroInventario->getTelefono());
                $em->persist($arTercero);
            }
        }

        return $arTercero;
    }

}