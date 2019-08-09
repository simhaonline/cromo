<?php


namespace App\Form\Type\RecursoHumano;


use App\Entity\RecursoHumano\RhuLicenciaTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LicenciaTipoType extends AbstractType
{

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuLicenciaTipo::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoLicenciaTipoPk", "tipo":"pk",    "ayuda":"Codigo del registro", "titulo":"ID"},
            {"campo":"nombre",              "tipo":"texto", "ayuda":"Nombre del registro", "titulo":"NOMBRE"},
            {"campo":"subTipo",             "tipo":"texto", "ayuda":"Subtipo del registro L = LICENCIA, I = INCAPACIDAD", "titulo":"SUBTIPO"},
            {"campo":"codigoConceptoFk",    "tipo":"texto", "ayuda":"Codigo concepto ",    "titulo":"CONCEPTO"},
            {"campo":"abreviatura",         "tipo":"texto", "ayuda":"Abreviatura del nombre del registro ",    "titulo":"ABR"}
        ]';
        return $campos;
    }
}