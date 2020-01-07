<?php


namespace App\Repository\Turno;


use App\Entity\Turno\TurClienteIca;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class TurClienteIcaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TurClienteIca::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        if ($filtros) {
            $codigoClienteIca = $filtros['codigoClienteIca'] ?? null;
            $numeroIdentificacion = $filtros['numeroIdentificacion'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurClienteIca::class, 'ci')
            ->select('ci.codigoClienteIcaPk')
            ->addSelect('ci.codigoDane')
            ->addSelect('ci.codigoInterface')
            ->addSelect('ci.tarIca')
            ->addSelect('ci.porIca')
            ->addSelect('c.numeroIdentificacion')
            ->addSelect('c.nombreCorto')
            ->addSelect('ciu.nombre as ciudad')
            ->addSelect('i.nombre  as item')
            ->leftJoin('ci.clienteRel', 'c')
            ->leftJoin('ci.ciudadRel', 'ciu')
            ->leftJoin('ci.itemRel', 'i');

        if ($codigoClienteIca) {
            $queryBuilder->andWhere("ci.codigoClienteIcaPk = '{$codigoClienteIca}'");
        }
        if ($numeroIdentificacion) {
            $queryBuilder->andWhere("c.numeroIdentificacion = '{$numeroIdentificacion}'");
        }
        $queryBuilder->addOrderBy('ci.codigoClienteIcaPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();

    }

    public function eliminar($arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrDetallesSeleccionados) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $ar = $em->getRepository(TurClienteIca::class)->find($codigo);
                    if ($ar) {
                        $em->remove($ar);
                    }
                }
                try {
                    $em->flush();
                } catch (\Exception $e) {
                    Mensajes::error('No se puede eliminar, el registro se encuentra en uso en el sistema');
                }
            }
        }
    }

    public function tarifaIca($codigoCliente, $codigoCiudad, $codigoServicioErp = "", $codigoConceptoServicio = "")
    {
        $em = $this->getEntityManager();
        $tarIca = 0;
        $query = $em->createQueryBuilder()->from(TurClienteIca::class, "ci")
            ->select("ci.tarIca")
            ->where("ci.codigoClienteIcaPk <> 0");
        if ($codigoCliente != "") {
            $query->where("ci.codigoClienteFk = {$codigoCliente}");
        }
        if ($codigoCiudad != "") {
            $query->andWhere("ci.codigoCiudadFk = {$codigoCiudad}");
        }
        if ($codigoServicioErp != "" || $codigoConceptoServicio != "") {
            $query->andWhere("ci.codigoServicioErp = '{$codigoServicioErp}' OR ci.codigoConceptoFk = {$codigoConceptoServicio}");
        }

        $arrResult = $query->getQuery()->getResult();
        if ($arrResult) {
            $tarIca = $arrResult[0]["tarIca"];
        }

        return $tarIca;

    }
}