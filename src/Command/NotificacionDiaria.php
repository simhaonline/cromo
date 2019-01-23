<?php
namespace App\Command;

use App\Controller\Estructura\FuncionesController;
use App\Entity\Inventario\InvLote;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NotificacionDiaria extends Command{

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
        $this->setName("app:notificacion-diaria")
             ->setDescription("Ejecutar notificaciones diarias")
             ->setHelp("Comando creado para crear notificaciones diarias");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->entityManager;
        //---------Inventario

        //Mercancia vencida en bodega
        $estado = $em->getRepository(InvLote::class)->notificacionDiariaMercanciaVencidaBodega();
        if($estado) {
            FuncionesController::crearNotificacion(2);
        }
        echo "Se generaron las notificaciones";
        /*$users = $this->entityManager->getRepository("App:Usuario\Usuario")->findAll();
        foreach ($users as $user){
            echo "{$user->getCodigoUsuarioPk()}\n";
        }*/
        exit();
    }
}