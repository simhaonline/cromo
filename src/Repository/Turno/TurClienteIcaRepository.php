<?php


namespace App\Repository\Turno;


use App\Entity\Turno\TurClienteIca;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class TurClienteIcaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TurClienteIca::class);
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
            $query->andWhere("ci.codigoServicioErp = '{$codigoServicioErp}' OR ci.codigoConceptoServicioFk = {$codigoConceptoServicio}");
        }

        $arrResult = $query->getQuery()->getResult();
        if ($arrResult) {
            $tarIca = $arrResult[0]["tarIca"];
        }

        return $tarIca;

    }
}