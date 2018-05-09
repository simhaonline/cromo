<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

//Funciones personalizadas para twig
class AppExtension extends AbstractExtension
{

    public function getFunctions(){
        return [
            new \Twig_Function('isBoolean',[$this,'isBoolean']),
            new \Twig_Function('isDate',[$this,'isDate']),
            new \Twig_Function('boolAnswer',[$this,'boolAnswer']),
            new \Twig_Function('dateAnswer',[$this,'dateAnswer']),
            new \Twig_Function('splitByUnderscore',[$this,'splitByUnderscore']),
            new \Twig_Function('isSelected',[$this,'isSelected']),
            new \Twig_Function('isPk',[$this,'isPk']),
        ];
    }

    public function isBoolean($dato){
        if(is_bool($dato)){
            return true;
        } else {
            return false;
        }
    }

    public function isDate($dato){
        if($dato instanceof  \DateTime){
            return true;
        } else {
            return false;
        }
    }

    public function boolAnswer($dato)
    {
        if ($dato) {
            return 'SI';
        } else {
            return 'NO';
        }
    }

    public function dateAnswer($dato){
        if($dato){
            return $dato->format('Y-m-d');
        } else {
            return '';
        }
    }

    public function splitByUnderscore($dato){
        $dato = preg_split('/(?=[A-Z])/',$dato);
        $dato = implode('_', $dato);
        return strtoupper($dato);
    }

    public function isSelected($dato){
        if($dato){
            return 'checked';
        } else {
            return '';
        }
    }

    public function isPk($dato){
        if(preg_match('/Pk/', $dato)){
            return true;
        } else {
            return false;
        }

    }
}