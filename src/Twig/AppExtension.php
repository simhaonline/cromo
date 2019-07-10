<?php

namespace App\Twig;

use App\Utilidades\Estandares;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Session\Session;
use Twig\Extension\AbstractExtension;

//Funciones personalizadas para twig
class AppExtension extends AbstractExtension
{

    public function getEnv($env)
    {
        return getenv($env);
    }

    public function getFunctions()
    {
        return [
            new \Twig_Function('validarTipo', [$this, 'validarTipo']),
            new \Twig_Function('validarTipoDetalle', [$this, 'validarTipoDetalle']),
            new \Twig_Function('separarPorGuionbajo', [$this, 'separarPorGuionbajo']),
            new \Twig_Function('validarSeleccionado', [$this, 'validarSeleccionado']),
            new \Twig_Function('esId', [$this, 'esId']),
            new \Twig_Function('esEstado', [$this, 'esEstado']),
            new \Twig_Function('mod', [$this, 'mod']),
            new \Twig_Function('validarLongitudTexto', [$this, 'validarLongitudTexto']),
            new \Twig_Function('crearTitulo', [$this, 'crearTitulo']),
            new \Twig_Function('llenarArray', [$this, 'llenarArray']),
            new \Twig_Function('notificar', [$this, 'getNotifies']),
            new \Twig_Function('encriptar', [$this, 'encriptar']),
            new \Twig_Function('env', [$this, "getEnv"]),
            new \Twig_Function('mesATexto', [$this, "mesATexto"]),
            new \Twig_Function('crearEncabezadoTabla', [$this, "crearEncabezadoTabla"]),
            new \Twig_Function('crearCuerpoTabla', [$this, "crearCuerpoTabla"]),
            new \Twig_Function('generarArrRegistros', [$this, "generarArrRegistros"]),
            new \Twig_Function('validarBooleano', [$this, "validarBooleano"]),
            new \Twig_Function('obtenerFormView', [$this, "obtenerFormView"]),
            new \Twig_Function('validarRuta', [$this, "validarRuta"]),
            new \Twig_Function('componerDiaProgramacionEmpleado', [$this, "componerDiaProgramacionEmpleado"]),
        ];
    }

    public function getFilters()
    {
        return [
            new \Twig_Filter('objectToArray', [$this, 'objectToArray']),
        ];
    }

    /**
     * @author Andres Acevedo
     * @param $dato
     * @param string $tabla
     * @return string
     */
    public function validarTipo($dato, $tabla = "")
    {
        if ($tabla != '') {
            if ($this->esFecha($dato)) {
                $tabla .= "<td style='text-align: left;'>" . $dato->format('Y-m-d') . "</td>";
            } elseif ($this->esBooleano($dato)) {
                if ($dato) {
                    $tabla .= "<td style='text-align: left;'>" . 'SI' . "</td>";
                } else {
                    $tabla .= "<td style='text-align: left;'>" . 'NO' . "</td>";
                }
            } elseif (is_numeric($dato)) {
                $tabla .= "<td style='text-align: right;'>" . $dato . "</td>";
            } else {
                $tabla .= "<td style='text-align: left;'>" . $dato . "</td>";
            }
            return $tabla;
        } else {
            if ($this->esFecha($dato)) {
                echo "<td style='text-align: left;'>" . $dato->format('Y-m-d') . "</td>";
            } elseif ($this->esBooleano($dato)) {
                if ($dato) {
                    echo "<td style='text-align: left;'>" . 'SI' . "</td>";
                } else {
                    echo "<td style='text-align: left;'>" . 'NO' . "</td>";
                }
            } elseif (is_numeric($dato)) {
                echo "<td style='text-align: left;'>" . $dato . "</td>";
            } else {
                echo "<td style='text-align: left;'>" . $dato . "</td>";
            }
        }
    }

    /**
     * @author Andres Acevedo
     * @param $dato
     */
    public function validarTipoDetalle($dato)
    {
        if ($this->esFecha($dato)) {
            echo "<td style='text-align: left;'>" . $dato->format('Y-m-d') . "</td>";
        } elseif ($this->esBooleano($dato)) {
            if ($dato) {
                echo "<td style='text-align: left;'>" . 'SI' . "</td>";
            } else {
                echo "<td style='text-align: left;'>" . 'NO' . "</td>";
            }
        } elseif (is_numeric($dato)) {
            echo "<td style='text-align: left;'>" . $dato . "</td>";
        } else {
            echo "<td style='text-align: left;'>" . $dato . "</td>";
        }
    }

    public function validarBooleano($dato)
    {
        if ($dato == 1) {
            echo 'SI';
        } else {
            echo 'NO';
        }
    }

    /**
     * @author Andres Acevedo
     * @param $dato
     * @return string
     */
    public function separarPorGuionbajo($dato)
    {
        $dato = preg_split('/(?=[A-Z])/', $dato);
        $dato = implode('_', $dato);
        return strtoupper($dato);
    }

    /**
     * @author Andres Acevedo
     * @param $dato
     * @return string
     */
    public function validarSeleccionado($dato)
    {
        if ($dato) {
            return 'checked';
        } else {
            return '';
        }
    }

    /**
     * @author Andres Acevedo
     * @param $dato
     * @return mixed
     */
    public function mesATexto($dato)
    {
        $meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
        return $meses[$dato - 1];
    }

    /**
     * @author Andres Acevedo
     * @param $dato
     * @return bool
     */
    public function esId($dato)
    {
        if (preg_match('/Pk/', $dato)) {
            return true;
        } else {
            return false;
        }

    }

    public function encriptar($dato)
    {
        return str_replace('/', '&', password_hash($dato, PASSWORD_BCRYPT));
    }

    public function esEstado($dato)
    {
        if (preg_match('/estado/', $dato)) {
            if (preg_match('/Anulado/', $dato)) {
                return true;
            } elseif (preg_match('/Aprobado/', $dato)) {
                return true;
            } elseif (preg_match('/Autorizado/', $dato)) {
                return true;
            }
        } else {
            return false;
        }
    }

    public function mod($dato, $numero)
    {
        if (($dato % $numero) == 0) {
            return true;
        }
    }

    public function validarLongitudTexto($dato)
    {
        if (!$dato instanceof \DateTime) {
            if (strlen($dato) > 8) {
                return true;
            }
        }
    }

    public function obtenerFormView(FormView $formView, string $prop)
    {
        return $formView->children[$prop];
    }

    /**
     * @author Andres Acevedo
     * @param $arrOpciones
     * @param $request
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function generarArrRegistros($arrOpciones, $request)
    {
        global $kernel;
        $paginator = $kernel->getContainer()->get('knp_paginator');
        return $paginator->paginate($arrOpciones['query'], $request->query->getInt('page', 1), 3);
    }

    /**
     * @author Andres Acevedo
     * @param $arrOpciones
     * @param $check
     */
    public function crearEncabezadoTabla($arrOpciones, $check)
    {
        $campos = json_decode($arrOpciones['json']);
        $header = '';
        foreach ($campos as $campo) {
            $header .= "<th title='" . $campo->ayuda . "'>" . $campo->titulo . "</th>";
        }
        $header .= "<th></th><th></th>";
        if ($check) {
            $header .= "<th></th>";
        }
        $header .= "</tr>";
        echo $header;
    }

    public function crearCuerpoTabla($arrOpciones, $check, $request)
    {
        global $kernel;
        $router = $kernel->getContainer()->get('router');
        $pk = 0;
        $arRegistros = $this->generarArrRegistros($arrOpciones, $request);
        $body = '';
        foreach ($arRegistros as $arRegistro) {
            $body .= "<tr>";
            foreach ($arRegistro as $key => $dato) {
                $body = $this->validarTipo($dato, $body);
                if (strpos($key, 'Pk')) {
                    $pk = $dato;
                }
            }
            $body .= "<td style='text-align: center;'><a href='" . $router->generate($arrOpciones['ruta'] . 'nuevo', ['id' => $pk]) . "'><i class='fa fa-edit' style='font-size: large;color: black;'></i></a></td>";
            $body .= "<td style='text-align: center;'><a href='" . $router->generate($arrOpciones['ruta'] . 'detalle', ['id' => $pk]) . "'><i class='fa fa-share-square-o' style='font-size: large;color: black;'></i></a></td>";
            if ($check) {
                $body .= "<td style='text-align: center;'><input type='checkbox' name='ChkSeleccionar[]' value='" . $pk . "'></td>";
            }
            $body .= "</tr>";
        }
        echo $body;
    }

    public function llenarArray($array, $alias, $dato)
    {
        $array[$alias] = $dato;
        return $array;
    }

    /**
     * Esta función se encarga de imprimir las notificaciones para usuario (Mensajes).
     * @return string
     */
    public function getNotifies()
    {
        $session = new Session();
        $flashes = $session->getFlashBag()->all();
        $html = [];
        foreach ($flashes as $type => $messages) {
            foreach ($messages AS $message) {
                $span = $this->createTag("span", "&times;", ['aria-hidden' => 'true']);
                $button = $this->createTag("button", $span, ['class' => 'close', 'data-dismiss' => 'alert', 'aria-label' => 'Close']);
                $alert = $this->createTag("div", $button . $message, ['class' => "alert alert-{$type}", 'data','style' => 'margin-top:5px;margin-bottom:2px;margin-left:5px;margin-right:5px;']);
                $html[] = $alert;
            }
        }
        $session->getFlashBag()->clear();
        return implode('', $html);
    }

    /**
     * @param $arRegistro
     * @return mixed
     */
    public function objectToArray($arRegistro)
    {
        $arrOpciones = (array)$arRegistro;
        $arrRegistro = [];
        foreach ($arrOpciones as $key => $dato) {
            if ($key !== 'infoLog') {
                $campo = explode("\x00", $key)[2];

                if (strpos($campo, 'Rel') === false) {
                    $arrRegistro[$campo] = $dato;
                }
            }
        }
        return $arrRegistro;
    }


    /**
     * @param $ruta
     * @return bool
     */
    public function validarRuta($ruta){
        return (null === Estandares::getRouter()->getRouteCollection()->get($ruta)) ? false : true;

    }

    /**
     * Esta función nos permite obtener código html sin violar estandares de mezcla de código.
     * @param $tag
     * @param string $content
     * @param array $attrs
     * @return string
     */
    private function createTag($tag, $content = '', $attrs = [])
    {
        $attrs = implode(" ", array_map(function ($attr, $value) {
            return "{$attr}=\"{$value}\"";
        }, array_keys($attrs), $attrs));
        return "<{$tag}" . ($attrs ? " {$attrs}" : "") . ">{$content}</{$tag}>";
    }

    private function esFecha($dato)
    {
        if ($dato instanceof \DateTime) {
            return true;
        } else {
            return false;
        }
    }

    private function esBooleano($dato)
    {
        if (is_bool($dato)) {
            return true;
        } else {
            return false;
        }
    }

    public function componerDiaProgramacionEmpleado($arProgramacion, $dia)
    {
        $valorDia = null;
        $nombreFuncion = "getDia{$dia}";
        if (method_exists($arProgramacion, $nombreFuncion)) {
            $valorDia = call_user_func_array([$arProgramacion, $nombreFuncion], []);
        }

        return $valorDia;
    }
}