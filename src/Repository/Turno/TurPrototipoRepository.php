<?php

namespace App\Repository\Turno;


use App\Controller\Estructura\FuncionesController;
use App\Entity\Turno\TurCliente;
use App\Entity\Turno\TurFestivo;
use App\Entity\Turno\TurPrototipo;
use App\Entity\Turno\TurSecuencia;
use App\Entity\Turno\TurSimulacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TurPrototipoRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TurPrototipo::class);
    }

    public function listaProgramar($codigoContratoDetalle){
        $session = new Session();
        $arPrototipos = null;
        if($codigoContratoDetalle) {
            $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurPrototipo::class, 'p')
                ->select('p.codigoPrototipoPk')
                ->addSelect('e.numeroIdentificacion as empleadoNumeroIdentificacion')
                ->addSelect('e.nombreCorto as empleadoNombreCorto')
                ->addSelect('p.fechaInicioSecuencia')
                ->addSelect('p.inicioSecuencia')
                ->addSelect('p.codigoSecuenciaFk')
                ->leftJoin('p.empleadoRel', 'e')
                ->where("p.codigoContratoDetalleFk = {$codigoContratoDetalle}");
            $arPrototipos = $queryBuilder->getQuery()->getResult();
        }
        return $arPrototipos;
    }

    public function generarSimulacion($arPedidoDetalle, $fechaProgramacion) {
        $em = $this->getEntityManager();

        $intDiaInicial = intval($fechaProgramacion->format('d'));
        $intDiaFinal = intval($fechaProgramacion->format('t'));
        $intMesInicial = intval($fechaProgramacion->format('m'));
        $arrFestivos = $em->getRepository(TurFestivo::class)->fechaArray($fechaProgramacion->format('Y-m-') . $intDiaInicial, $fechaProgramacion->format('Y-m-') . $intDiaFinal);

        $arPrototipos = $em->getRepository(TurPrototipo::class)->findBy(['codigoContratoDetalleFk' => $arPedidoDetalle->getCodigoContratoDetalleFk()]);
        foreach ($arPrototipos as $arPrototipo) {
            $arSecuencia = $arPrototipo->getSecuenciaRel();
            $arrSecuencias = FuncionesController::turnosSecuencia($arSecuencia);

            $arSimulacion = new TurSimulacion();
            $arSimulacion->setPedidoDetalleRel($arPedidoDetalle);
            $arSimulacion->setAnio($fechaProgramacion->format('Y'));
            $arSimulacion->setMes($fechaProgramacion->format('m'));

            //$arSimulacionDetalle->setRecursoRel($arServicioDetalleRecurso->getRecursoRel());
            //$arSimulacionDetalle->setUsuario($usuario);
            $fechaInicial = $arPrototipo->getFechaInicioSecuencia()->format('Y-m-d');
            $intDiaInicialRecurso = intval($arPrototipo->getFechaInicioSecuencia()->format('d'));
            $intMesInicialRecurso = intval($arPrototipo->getFechaInicioSecuencia()->format('m'));
            $intervalo = $arSecuencia->getDias(); # Cada cuanto se repetira la secuencia.
            $posicion = FuncionesController::devuelvePosicionInicialSecuencia($arPrototipo->getInicioSecuencia(), $intervalo, $fechaInicial, $fechaProgramacion->format('Y-m-d'));
            for ($i = $intDiaInicial; $i <= $intDiaFinal; $i++) {
                # Para no llenar dias anteriores a la fecha de inicio de la secuencia del recurso.
                if ($i < $intDiaInicialRecurso && $fechaProgramacion->format('Ym') == $arPrototipo->getFechaInicioSecuencia()->format('Ym')) {
                    continue;
                }
                $fecha = date_create(date("{$arSimulacion->getAnio()}-{$intMesInicial}-{$i}"));
                $turno = isset($arrSecuencias[$posicion]) ? $arrSecuencias[$posicion] : null;
                # Validamos si el turno es un día de la semana.
                if ($turnoDiaSemana = FuncionesController::validacionDiaSemama($fecha->format('N'), $arSecuencia)) {
                    $turno = $turnoDiaSemana;
                }

                # Validamos si el turno es festivo.
                if ($turnoFestivo = FuncionesController::validacionFestivo($fecha->format('Y-m-d'), $arrFestivos, $arSecuencia)) {
                    $turno = $turnoFestivo;
                }
                # Validamos si el día es domingo y si el siguiente día es festivo.
                if (intval($fecha->format('N')) == 7) {
                    if ($domingoFestivo = FuncionesController::validacionDomingoFestivo($fecha, $arrFestivos, $arSecuencia)) {
                        $turno = $domingoFestivo;
                    }
                }

                # Si la secuencia es homologada obtenemos el turno que le corresponde.
                /*if ($arSecuencia->getHomologar()) {
                    $nombreMetodo = "getTurno{$turno}";
                    if (method_exists($arPrototipo, $nombreMetodo)) {
                        $turno = call_user_func_array([$arPrototipo, $nombreMetodo], []);
                    }
                }*/

                if (method_exists($arSimulacion, "setDia{$i}")) {
                    call_user_func_array([$arSimulacion, "setDia{$i}"], [$turno]);
                }
                $posicion += 1;
                if ($posicion > $intervalo) {
                    $posicion = 1;
                }
            }
            $em->persist($arSimulacion);
        }
        $em->flush();

    }

    public function actualizar($arrControles)
    {
        $em = $this->getEntityManager();
        $arrCodigo = $arrControles['arrCodigo'];
        $arrSecuencia = $arrControles['cboSecuencia'];
        $arrInicioSecuencia = $arrControles['arrInicioSecuencia'];
        $arrFechaInicio = $arrControles['arrFechaInicio'];

        foreach ($arrCodigo as $codigo) {
            /** @var $arPrototipo TurPrototipo */
            $arPrototipo =$em->getRepository(TurPrototipo::class)->find($codigo);
            $arPrototipo->setInicioSecuencia($arrInicioSecuencia[$codigo]);
            $fecha = $arrFechaInicio[$codigo];
            $arPrototipo->setFechaInicioSecuencia(date_create($fecha));
            $codigoSecuencia = $arrSecuencia[$codigo];
            if($arPrototipo->getCodigoSecuenciaFk() != $codigoSecuencia) {
                $arSecuencia = $em->getRepository(TurSecuencia::class)->find($codigoSecuencia);
                $arPrototipo->setSecuenciaRel($arSecuencia);
            }
            $em->persist($arPrototipo);
        }
        $em->flush();
    }

}
