<?php


namespace App\Controller\Turno\Administracion\Comercial;


use App\Controller\Estructura\ControllerListenerGeneral;
use App\Entity\Turno\TurPedidoTipo;

class PedidoTipoController extends ControllerListenerGeneral
{
    protected $clase = TurPedidoTipo::class;
    protected $claseFormulario = PedidoTipoType::class;
    protected $claseNombre = "TurPedidoTipo";
    protected $modulo = "Turno";
    protected $funcion = "Administracion";
    protected $grupo = "comercial";
    protected $nombre = "PedidoTipo";
}