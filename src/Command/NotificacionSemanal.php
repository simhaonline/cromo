<?php
namespace App\Command;

use App\Controller\Estructura\FuncionesController;
use App\Entity\Inventario\InvItem;
use App\Entity\Inventario\InvLote;
use App\Entity\Inventario\InvMovimiento;
use App\Entity\Inventario\InvPedido;
use App\Entity\Inventario\InvRemision;
use App\Entity\Transporte\TteDespacho;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NotificacionSemanal extends Command{

    /**
     * @var EntityManagerInterface|null
     */
    private $entityManager = null;

    public function __construct(?string $name = null, EntityManagerInterface $om)
    {
        parent::__construct($name);
        $this->entityManager = $om;
    }

    protected function configure()
    {
        $this->setName("app:notificacion-semanal")
             ->setDescription("Ejecutar notificaciones semanales")
             ->setHelp("Comando creado para crear notificaciones semanales");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->entityManager;

        //---------Inventario---------------------------------------------
        //Item con stock minimo
        $arItemes = $em->getRepository(InvItem::class)->stockMinimo();
        foreach ($arItemes as $arItem) {
            FuncionesController::crearNotificacion(10, "Item: " . $arItem['codigoItemPk'] . "-" . $arItem['nombre'] . " stock[" . $arItem['cantidadDisponible'] . "] stock minimo[" . $arItem['stockMinimo'] ."]");
        }
        echo "Se generaron las notificaciones semanales";
        exit();
    }
}