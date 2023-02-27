<?php

namespace App\Command;

use App\Entity\Evento;
use App\Entity\Juego;
use App\Entity\Tramos;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Constraints\Choice;

#[AsCommand(
    name: 'do:evento',
    description: 'Un comando que crea un evento',
    hidden: false,
    aliases: ['eventoMaker']
)]
class EventoMakerCommand extends Command
{

    private $manager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        
        $this->manager = $entityManager;
    }

    protected function configure(): void
    {
        
        $this
            ->setHelp('Este comando te permite crear un evento, se le dice do:evento NOMBRE-EVENTO y un asistente te ayuda a crearlo')
            ->addArgument('nombre', InputArgument::OPTIONAL, 'El nombre del evento')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
            ->addArgument('tramo')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $progressBar = new ProgressBar($output);
        

        $evento = new Evento();
        $output->writeln([
            'Creador de Evento',
            '=================',
            '',
        ]);

        $progressBar->start(100);
        $nombre = $input->getArgument('nombre');

        /* NOMBRE */
        if (!$nombre) {
            // Si no nos ha dicho ya el nombre, se lo pedimos
            $nombre = $io->ask("¿Cómo se llamará el evento?");
        }
        $evento->setNombre($nombre);
        $progressBar->advance(25);
        $output->writeln(''); //Salto de línea
        
        /* NÚMERO DE ASISTENTES */
        $max = $io->ask("¿Cuál es el aforo máximo?","10");
        $evento->setNumMaxAsistentes(intval($max));
        $progressBar->advance(10);
        $output->writeln(''); //Salto de línea

        /* FECHA */
        $date = $io->ask('¿Cuándo será? (Fecha en formato: 2003-08-30)');
        $fecha = new DateTime($date);
        $evento->setFecha($fecha);
        $output->writeln(''); //Salto de línea
        
        /* TRAMO HORARIO */
        $tramos = $this->manager->getRepository(Tramos::class)->findAll();
        foreach ($tramos as $tramo) {
            # Los imprimimos como opciones
            $output->writeln($tramo->getId().' - '.$tramo);
        }

        $tramoElegido = $io->ask("¿Durante qué tramo será?");
        $input->setInteractive(true);
        $tramo = $this->manager->getRepository(Tramos::class)->find($tramoElegido);
        $evento->setTramo($tramo);
        $progressBar->advance(15);
        $io->writeln('');

        /* JUEGOS */
        $juegos = $this->manager->getRepository(Juego::class)->findAll();
        
        /* (1/2) */
        foreach ($juegos as $juego) {
            # Los imprimimos como opciones
            $output->writeln($juego->getId().' - '.$juego);
        }

        $juegoElegido = $io->ask("¿Qué juego se presentará? [1/2]");
        $input->setInteractive(true);
        $tramo = $this->manager->getRepository(Juego::class)->find($juegoElegido);
        $evento->setTramo($tramo);
        $progressBar->advance(15);
        $io->writeln('');

        /* (2/2) */
        foreach ($juegos as $juego) {
            # Los imprimimos como opciones
            $output->writeln($juego->getId().' - '.$juego);
        }
        $juegoElegido = $io->ask("¿Qué juego se presentará? [2/2]",null);
        $input->setInteractive(true);
        $tramo = $this->manager->getRepository(Juego::class)->find($juegoElegido);
        $evento->setTramo($tramo);
        $progressBar->advance(15);
        $io->writeln('');
        
        /* FIN*/
        $output->writeln('Nombre del evento: '.$nombre);
        $output->writeln('Participantes: '.$max);
        $output->writeln('Fecha: '.$fecha->format('d-m-Y'));
        $output->writeln('Tramo: '.$tramo);

        $progressBar->finish();
        $io->writeln('');
        $this->manager->persist($evento);
        $this->manager->flush();
    
        $io->writeln('Muy bien');
        $io->writeln('Ya hemos acabado!');

        $output->writeln('');

        $io->success('Evento creado! Echa un vistazo! Escribe do:evento para crear otro.');

        return Command::SUCCESS;
    }
    
}
