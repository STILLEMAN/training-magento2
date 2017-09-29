<?php
/**
 * Created by PhpStorm.
 * User: training
 * Date: 29/09/17
 * Time: 10:53
 */

namespace Training\Seller\Console\Command;


use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Training\Seller\Api\SellerRepositoryInterface;

class GetCommand extends Command
{

    const ID_OPTION = 'id';

    protected $sellerRepository;

    public function __construct(SellerRepositoryInterface $sellerRepository)
    {

        $this->sellerRepository = $sellerRepository;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('training:seller:get')
            ->setDescription('Display seller infos')
        ->setDefinition([
            new InputOption(
                self::ID_OPTION,
                '-i',
                InputOption::VALUE_REQUIRED,
                'Seller id'
            )
        ]);

        parent::configure();
    }


    public function execute(InputInterface $input, OutputInterface $output)
    {
        $idSeller = $input->getOption(self::ID_OPTION);

        if( is_null($idSeller) ){
            throw new \InvalidArgumentException('Argument ' . self::ID_OPTION .' absent');
        }
        $output->writeln("<info>Informations sur le seller n° $idSeller :</info>");
        $seller = $this->sellerRepository->getById($idSeller);
        $output->writeln("\tName : " . $seller->getName());
        $output->writeln("\tIdentifier : " . $seller->getIdentifier());
        $output->writeln("\tDescription : " . $seller->getDescription());

    }
}