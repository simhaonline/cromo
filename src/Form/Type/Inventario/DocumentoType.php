<?php

namespace App\Form\Type\Inventario;

use App\Entity\General\GenResolucion;
use App\Entity\Inventario\InvDocumento;
use App\Entity\Inventario\InvDocumentoTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DocumentoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoDocumentoPk',TextType::class,['label' => 'Codigo:','required' => true])
            ->add('nombre',TextType::class,['label' => 'Nombre: '])
            ->add('resolucionRel',EntityType::class,[
                'required' => false,
                'class' => GenResolucion::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->where('r.estadoActivo = true')
                        ->orderBy('r.numero', 'ASC');
                },
                'choice_label' => 'numero',
                'label' => 'Resolucion:'
            ])
            ->add('documentoTipoRel',EntityType::class,[
                'required' => true,
                'class' => InvDocumentoTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('st')
                        ->orderBy('st.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Documento tipo:'
            ])
            ->add('abreviatura',TextType::class,['label' => 'Abreviatura: '])
            ->add('prefijo',TextType::class,['label' => 'Prefijo: ', 'required' => false])
            ->add('consecutivo',TextType::class,['label' => 'Consecutivo: '])
            ->add('operacionInventario',ChoiceType::class,['choices' => ['SUMA' => '1','RESTA' => '-1', 'NEUTRO' => '0']])
            ->add('codigoComprobanteFk',TextType::class,['required' => false])
            ->add('codigoCuentaProveedorFk',TextType::class,['required' => false])
            ->add('codigoCuentaClienteFk',TextType::class,['required' => false])
            ->add('generaCostoPromedio')
            ->add('adicionar')
            ->add('adicionarOrden')
            ->add('adicionarPedido')
            ->add('adicionarRemision')
            ->add('adicionarImportacion')
            ->add('compraExtranjera')
            ->add('guardar',SubmitType::class,['label' => 'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InvDocumento::class,
        ]);
    }
}
