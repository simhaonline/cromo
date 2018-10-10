<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEmpleado;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuEmpleadoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuEmpleado::class);
    }

    /**
     * @return string
     */
    public function getRuta()
    {
        return 'recursohumano_administracion_empleado_empleado_';
    }

    public function camposPredeterminados()
    {
        $qb = $this->_em->createQueryBuilder()
            ->from('App:RecursoHumano\RHuEmpleado', 'e')
            ->select('e.codigoEmpleadoPk AS ID');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

    /**
     * @return array
     */
    public function parametrosLista()
    {
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuEmpleado::class, 're')
            ->select('re.codigoEmpleadoPk')
            ->addSelect('re.numeroIdentificacion')
            ->addSelect('re.nombreCorto')
            ->addSelect('re.codigoContratoFk')
            ->addSelect('re.telefono')
            ->addSelect('re.correo')
            ->addSelect('re.direccion')
            ->where('re.codigoEmpleadoPk <> 0');
        $arrOpciones = ['json' => '[
        {"campo":"codigoEmpleadoPk","ayuda":"Codigo del empleado","titulo":"ID"},
        {"campo":"numeroIdentificacion","ayuda":"Numero de identificacion del empleado","titulo":"IDENTIFICACION"},
        {"campo":"nombreCorto","ayuda":"Nombre del empleado","titulo":"NOMBRE"},
        {"campo":"codigoContratoFk","ayuda":"Codigo del contrato","titulo":"CONTRATO"},
        {"campo":"telefono","ayuda":"Telefono del empleado","titulo":"TELEFONO"},
        {"campo":"correo","ayuda":"Correo del empleado","titulo":"CORREO"},
        {"campo":"direccion","ayuda":"Direccion de residencia del empleado","titulo":"DIRECCION"}]',
            'query' => $queryBuilder, 'ruta' => $this->getRuta()];
        return $arrOpciones;
    }

    /**
     * @return mixed
     */
    public function parametrosExcel()
    {
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuEmpleado::class, 're')
            ->select('re.codigoEmpleadoPk')
            ->addSelect('re.numeroIdentificacion')
            ->addSelect('re.nombreCorto')
            ->addSelect('re.telefono')
            ->addSelect('re.correo')
            ->addSelect('re.direccion')
            ->where('re.codigoEmpleadoPk <> 0');
        return $queryBuilder->getQuery()->execute();
    }


}