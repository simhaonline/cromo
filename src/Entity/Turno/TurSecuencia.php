<?php


namespace App\Entity\Secuencia;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Secuencia\TurSecuenciaRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TurSecuencia
{
    public $infoLog = [
        "primaryKey" => "codigoSecuenciaPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_secuencia_pk", type="string", length=5)
     */
    private $codigoSecuenciaPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="dia_1", type="string", length=5, nullable=true)
     */
    private $dia1;

    /**
     * @ORM\Column(name="dia_2", type="string", length=5, nullable=true)
     */
    private $dia2;

    /**
     * @ORM\Column(name="dia_3", type="string", length=5, nullable=true)
     */
    private $dia3;

    /**
     * @ORM\Column(name="dia_4", type="string", length=5, nullable=true)
     */
    private $dia4;

    /**
     * @ORM\Column(name="dia_5", type="string", length=5, nullable=true)
     */
    private $dia5;

    /**
     * @ORM\Column(name="dia_6", type="string", length=5, nullable=true)
     */
    private $dia6;

    /**
     * @ORM\Column(name="dia_7", type="string", length=5, nullable=true)
     */
    private $dia7;

    /**
     * @ORM\Column(name="dia_8", type="string", length=5, nullable=true)
     */
    private $dia8;

    /**
     * @ORM\Column(name="dia_9", type="string", length=5, nullable=true)
     */
    private $dia9;

    /**
     * @ORM\Column(name="dia_10", type="string", length=5, nullable=true)
     */
    private $dia10;

    /**
     * @ORM\Column(name="dia_11", type="string", length=5, nullable=true)
     */
    private $dia11;

    /**
     * @ORM\Column(name="dia_12", type="string", length=5, nullable=true)
     */
    private $dia12;

    /**
     * @ORM\Column(name="dia_13", type="string", length=5, nullable=true)
     */
    private $dia13;

    /**
     * @ORM\Column(name="dia_14", type="string", length=5, nullable=true)
     */
    private $dia14;

    /**
     * @ORM\Column(name="dia_15", type="string", length=5, nullable=true)
     */
    private $dia15;

    /**
     * @ORM\Column(name="dia_16", type="string", length=5, nullable=true)
     */
    private $dia16;

    /**
     * @ORM\Column(name="dia_17", type="string", length=5, nullable=true)
     */
    private $dia17;

    /**
     * @ORM\Column(name="dia_18", type="string", length=5, nullable=true)
     */
    private $dia18;

    /**
     * @ORM\Column(name="dia_19", type="string", length=5, nullable=true)
     */
    private $dia19;

    /**
     * @ORM\Column(name="dia_20", type="string", length=5, nullable=true)
     */
    private $dia20;

    /**
     * @ORM\Column(name="dia_21", type="string", length=5, nullable=true)
     */
    private $dia21;

    /**
     * @ORM\Column(name="dia_22", type="string", length=5, nullable=true)
     */
    private $dia22;

    /**
     * @ORM\Column(name="dia_23", type="string", length=5, nullable=true)
     */
    private $dia23;

    /**
     * @ORM\Column(name="dia_24", type="string", length=5, nullable=true)
     */
    private $dia24;

    /**
     * @ORM\Column(name="dia_25", type="string", length=5, nullable=true)
     */
    private $dia25;

    /**
     * @ORM\Column(name="dia_26", type="string", length=5, nullable=true)
     */
    private $dia26;

    /**
     * @ORM\Column(name="dia_27", type="string", length=5, nullable=true)
     */
    private $dia27;

    /**
     * @ORM\Column(name="dia_28", type="string", length=5, nullable=true)
     */
    private $dia28;

    /**
     * @ORM\Column(name="dia_29", type="string", length=5, nullable=true)
     */
    private $dia29;

    /**
     * @ORM\Column(name="dia_30", type="string", length=5, nullable=true)
     */
    private $dia30;

    /**
     * @ORM\Column(name="dia_31", type="string", length=5, nullable=true)
     */
    private $dia31;

    /**
     * @ORM\Column(name="lunes", type="string", length=5, nullable=true)
     */
    private $lunes;

    /**
     * @ORM\Column(name="martes", type="string", length=5, nullable=true)
     */
    private $martes;

    /**
     * @ORM\Column(name="miercoles", type="string", length=5, nullable=true)
     */
    private $miercoles;

    /**
     * @ORM\Column(name="jueves", type="string", length=5, nullable=true)
     */
    private $jueves;

    /**
     * @ORM\Column(name="viernes", type="string", length=5, nullable=true)
     */
    private $viernes;

    /**
     * @ORM\Column(name="sabado", type="string", length=5, nullable=true)
     */
    private $sabado;

    /**
     * @ORM\Column(name="domingo", type="string", length=5, nullable=true)
     */
    private $domingo;

    /**
     * @ORM\Column(name="festivo", type="string", length=5, nullable=true)
     */
    private $festivo;

    /**
     * @ORM\Column(name="domingo_festivo", type="string", length=5, nullable=true)
     */
    private $domingoFestivo;

    /**
     * @ORM\Column(name="horas", type="integer", nullable=true)
     */
    private $horas = 0;

    /**
     * @ORM\Column(name="dias", type="integer", nullable=true)
     */
    private $dias = 0;

    /**
     * @return mixed
     */
    public function getCodigoSecuenciaPk()
    {
        return $this->codigoSecuenciaPk;
    }

    /**
     * @param mixed $codigoSecuenciaPk
     */
    public function setCodigoSecuenciaPk($codigoSecuenciaPk): void
    {
        $this->codigoSecuenciaPk = $codigoSecuenciaPk;
    }

    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param mixed $nombre
     */
    public function setNombre($nombre): void
    {
        $this->nombre = $nombre;
    }

    /**
     * @return mixed
     */
    public function getDia1()
    {
        return $this->dia1;
    }

    /**
     * @param mixed $dia1
     */
    public function setDia1($dia1): void
    {
        $this->dia1 = $dia1;
    }

    /**
     * @return mixed
     */
    public function getDia2()
    {
        return $this->dia2;
    }

    /**
     * @param mixed $dia2
     */
    public function setDia2($dia2): void
    {
        $this->dia2 = $dia2;
    }

    /**
     * @return mixed
     */
    public function getDia3()
    {
        return $this->dia3;
    }

    /**
     * @param mixed $dia3
     */
    public function setDia3($dia3): void
    {
        $this->dia3 = $dia3;
    }

    /**
     * @return mixed
     */
    public function getDia4()
    {
        return $this->dia4;
    }

    /**
     * @param mixed $dia4
     */
    public function setDia4($dia4): void
    {
        $this->dia4 = $dia4;
    }

    /**
     * @return mixed
     */
    public function getDia5()
    {
        return $this->dia5;
    }

    /**
     * @param mixed $dia5
     */
    public function setDia5($dia5): void
    {
        $this->dia5 = $dia5;
    }

    /**
     * @return mixed
     */
    public function getDia6()
    {
        return $this->dia6;
    }

    /**
     * @param mixed $dia6
     */
    public function setDia6($dia6): void
    {
        $this->dia6 = $dia6;
    }

    /**
     * @return mixed
     */
    public function getDia7()
    {
        return $this->dia7;
    }

    /**
     * @param mixed $dia7
     */
    public function setDia7($dia7): void
    {
        $this->dia7 = $dia7;
    }

    /**
     * @return mixed
     */
    public function getDia8()
    {
        return $this->dia8;
    }

    /**
     * @param mixed $dia8
     */
    public function setDia8($dia8): void
    {
        $this->dia8 = $dia8;
    }

    /**
     * @return mixed
     */
    public function getDia9()
    {
        return $this->dia9;
    }

    /**
     * @param mixed $dia9
     */
    public function setDia9($dia9): void
    {
        $this->dia9 = $dia9;
    }

    /**
     * @return mixed
     */
    public function getDia10()
    {
        return $this->dia10;
    }

    /**
     * @param mixed $dia10
     */
    public function setDia10($dia10): void
    {
        $this->dia10 = $dia10;
    }

    /**
     * @return mixed
     */
    public function getDia11()
    {
        return $this->dia11;
    }

    /**
     * @param mixed $dia11
     */
    public function setDia11($dia11): void
    {
        $this->dia11 = $dia11;
    }

    /**
     * @return mixed
     */
    public function getDia12()
    {
        return $this->dia12;
    }

    /**
     * @param mixed $dia12
     */
    public function setDia12($dia12): void
    {
        $this->dia12 = $dia12;
    }

    /**
     * @return mixed
     */
    public function getDia13()
    {
        return $this->dia13;
    }

    /**
     * @param mixed $dia13
     */
    public function setDia13($dia13): void
    {
        $this->dia13 = $dia13;
    }

    /**
     * @return mixed
     */
    public function getDia14()
    {
        return $this->dia14;
    }

    /**
     * @param mixed $dia14
     */
    public function setDia14($dia14): void
    {
        $this->dia14 = $dia14;
    }

    /**
     * @return mixed
     */
    public function getDia15()
    {
        return $this->dia15;
    }

    /**
     * @param mixed $dia15
     */
    public function setDia15($dia15): void
    {
        $this->dia15 = $dia15;
    }

    /**
     * @return mixed
     */
    public function getDia16()
    {
        return $this->dia16;
    }

    /**
     * @param mixed $dia16
     */
    public function setDia16($dia16): void
    {
        $this->dia16 = $dia16;
    }

    /**
     * @return mixed
     */
    public function getDia17()
    {
        return $this->dia17;
    }

    /**
     * @param mixed $dia17
     */
    public function setDia17($dia17): void
    {
        $this->dia17 = $dia17;
    }

    /**
     * @return mixed
     */
    public function getDia18()
    {
        return $this->dia18;
    }

    /**
     * @param mixed $dia18
     */
    public function setDia18($dia18): void
    {
        $this->dia18 = $dia18;
    }

    /**
     * @return mixed
     */
    public function getDia19()
    {
        return $this->dia19;
    }

    /**
     * @param mixed $dia19
     */
    public function setDia19($dia19): void
    {
        $this->dia19 = $dia19;
    }

    /**
     * @return mixed
     */
    public function getDia20()
    {
        return $this->dia20;
    }

    /**
     * @param mixed $dia20
     */
    public function setDia20($dia20): void
    {
        $this->dia20 = $dia20;
    }

    /**
     * @return mixed
     */
    public function getDia21()
    {
        return $this->dia21;
    }

    /**
     * @param mixed $dia21
     */
    public function setDia21($dia21): void
    {
        $this->dia21 = $dia21;
    }

    /**
     * @return mixed
     */
    public function getDia22()
    {
        return $this->dia22;
    }

    /**
     * @param mixed $dia22
     */
    public function setDia22($dia22): void
    {
        $this->dia22 = $dia22;
    }

    /**
     * @return mixed
     */
    public function getDia23()
    {
        return $this->dia23;
    }

    /**
     * @param mixed $dia23
     */
    public function setDia23($dia23): void
    {
        $this->dia23 = $dia23;
    }

    /**
     * @return mixed
     */
    public function getDia24()
    {
        return $this->dia24;
    }

    /**
     * @param mixed $dia24
     */
    public function setDia24($dia24): void
    {
        $this->dia24 = $dia24;
    }

    /**
     * @return mixed
     */
    public function getDia25()
    {
        return $this->dia25;
    }

    /**
     * @param mixed $dia25
     */
    public function setDia25($dia25): void
    {
        $this->dia25 = $dia25;
    }

    /**
     * @return mixed
     */
    public function getDia26()
    {
        return $this->dia26;
    }

    /**
     * @param mixed $dia26
     */
    public function setDia26($dia26): void
    {
        $this->dia26 = $dia26;
    }

    /**
     * @return mixed
     */
    public function getDia27()
    {
        return $this->dia27;
    }

    /**
     * @param mixed $dia27
     */
    public function setDia27($dia27): void
    {
        $this->dia27 = $dia27;
    }

    /**
     * @return mixed
     */
    public function getDia28()
    {
        return $this->dia28;
    }

    /**
     * @param mixed $dia28
     */
    public function setDia28($dia28): void
    {
        $this->dia28 = $dia28;
    }

    /**
     * @return mixed
     */
    public function getDia29()
    {
        return $this->dia29;
    }

    /**
     * @param mixed $dia29
     */
    public function setDia29($dia29): void
    {
        $this->dia29 = $dia29;
    }

    /**
     * @return mixed
     */
    public function getDia30()
    {
        return $this->dia30;
    }

    /**
     * @param mixed $dia30
     */
    public function setDia30($dia30): void
    {
        $this->dia30 = $dia30;
    }

    /**
     * @return mixed
     */
    public function getDia31()
    {
        return $this->dia31;
    }

    /**
     * @param mixed $dia31
     */
    public function setDia31($dia31): void
    {
        $this->dia31 = $dia31;
    }

    /**
     * @return mixed
     */
    public function getLunes()
    {
        return $this->lunes;
    }

    /**
     * @param mixed $lunes
     */
    public function setLunes($lunes): void
    {
        $this->lunes = $lunes;
    }

    /**
     * @return mixed
     */
    public function getMartes()
    {
        return $this->martes;
    }

    /**
     * @param mixed $martes
     */
    public function setMartes($martes): void
    {
        $this->martes = $martes;
    }

    /**
     * @return mixed
     */
    public function getMiercoles()
    {
        return $this->miercoles;
    }

    /**
     * @param mixed $miercoles
     */
    public function setMiercoles($miercoles): void
    {
        $this->miercoles = $miercoles;
    }

    /**
     * @return mixed
     */
    public function getJueves()
    {
        return $this->jueves;
    }

    /**
     * @param mixed $jueves
     */
    public function setJueves($jueves): void
    {
        $this->jueves = $jueves;
    }

    /**
     * @return mixed
     */
    public function getViernes()
    {
        return $this->viernes;
    }

    /**
     * @param mixed $viernes
     */
    public function setViernes($viernes): void
    {
        $this->viernes = $viernes;
    }

    /**
     * @return mixed
     */
    public function getSabado()
    {
        return $this->sabado;
    }

    /**
     * @param mixed $sabado
     */
    public function setSabado($sabado): void
    {
        $this->sabado = $sabado;
    }

    /**
     * @return mixed
     */
    public function getDomingo()
    {
        return $this->domingo;
    }

    /**
     * @param mixed $domingo
     */
    public function setDomingo($domingo): void
    {
        $this->domingo = $domingo;
    }

    /**
     * @return mixed
     */
    public function getFestivo()
    {
        return $this->festivo;
    }

    /**
     * @param mixed $festivo
     */
    public function setFestivo($festivo): void
    {
        $this->festivo = $festivo;
    }

    /**
     * @return mixed
     */
    public function getDomingoFestivo()
    {
        return $this->domingoFestivo;
    }

    /**
     * @param mixed $domingoFestivo
     */
    public function setDomingoFestivo($domingoFestivo): void
    {
        $this->domingoFestivo = $domingoFestivo;
    }

    /**
     * @return mixed
     */
    public function getHoras()
    {
        return $this->horas;
    }

    /**
     * @param mixed $horas
     */
    public function setHoras($horas): void
    {
        $this->horas = $horas;
    }

    /**
     * @return mixed
     */
    public function getDias()
    {
        return $this->dias;
    }

    /**
     * @param mixed $dias
     */
    public function setDias($dias): void
    {
        $this->dias = $dias;
    }



}

