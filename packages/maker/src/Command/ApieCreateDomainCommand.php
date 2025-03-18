<?php
namespace Apie\Maker\Command;

use Apie\Core\BoundedContext\BoundedContextHashmap;
use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\Identifiers\PascalCaseSlug;
use Apie\Core\Other\ActualFileWriter;
use Apie\Core\Other\FileWriterInterface;
use Apie\Maker\CodeGenerators\CreateDomainObject;
use Apie\Maker\Dtos\DomainObjectDto;
use Apie\Maker\Enums\IdType;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'apie:create-domain-object', description: 'Create a new domain object.')]
class ApieCreateDomainCommand extends Command
{
    public function __construct(
        private readonly BoundedContextHashmap $boundedContextHashmap,
        private readonly CreateDomainObject $createDomainObject,
        private readonly FileWriterInterface $fileWriter = new ActualFileWriter(),
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument(
            'name',
            InputArgument::REQUIRED,
            description: 'name of the new class',
        );
        $this->addOption(
            'bounded-context',
            'b',
            InputOption::VALUE_REQUIRED,
            description: 'Add to bounded context',
            default: $this->boundedContextHashmap->getIterator()->key()
        );
        $this->addOption(
            'id-type',
            'i',
            InputOption::VALUE_REQUIRED,
            description: 'Type of the identifier',
            default: IdType::Ulid->name
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $object = new DomainObjectDto(
            new PascalCaseSlug($input->getArgument('name')),
            new BoundedContextId($input->getOption('bounded-context')),
            IdType::tryFromName($input->getOption('id-type')),
            true
        );
        
        $identifierPath = $this->createDomainObject->getIdentifierPath($object);
        $output->writeln(sprintf('Creating "%s"', $identifierPath));
        $this->fileWriter->writeFile(
            $identifierPath,
            $this->createDomainObject->generateDomainIdentifierCode($object)
        );
        $output->writeln('Done!');

        $domainObjectPath = $this->createDomainObject->getDomainObjectPath($object);
        $output->writeln(sprintf('Creating "%s"', $domainObjectPath));
        $this->fileWriter->writeFile(
            $domainObjectPath,
            $this->createDomainObject->generateDomainObjectCode($object)
        );
        $output->writeln('Done!');
        return Command::SUCCESS;
    }
}
