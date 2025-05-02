<?php
namespace Apie\AiInstructor;

use Apie\Core\Entities\EntityInterface;
use Apie\Core\ValueObjects\NonEmptyString;
use Apie\TypeConverter\ReflectionTypeFactory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

#[AsCommand(name: 'apie:ai-playground', description: 'Run a playground to call an LLM.')]
class AiPlaygroundCommand extends Command
{
    public function __construct(
        private readonly AiInstructor $aiInstructor
    ) {
        parent::__construct();
    }

    public function configure(): void
    {
        $this->addOption(
            'system',
            null,
            InputOption::VALUE_OPTIONAL,
            'system prompt to use'
        );
        $this->addOption(
            'user',
            null,
            InputOption::VALUE_OPTIONAL,
            'user prompt to use'
        );
        $this->addOption(
            'model',
            null,
            InputOption::VALUE_OPTIONAL,
            'LLM model picked'
        );
        $this->addOption(
            'type',
            null,
            InputOption::VALUE_OPTIONAL,
            'PHP typehint to instruct'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $systemPrompt = (string) $input->getOption('system');
        if (!$systemPrompt) {
            $question = new Question(
                'Please enter the system prompt',
                'You are an API that creates instances in a specific predefined format depending on the user input.'
            );

            $systemPrompt = $helper->ask($input, $output, $question);
        }
        $userPrompt = (string) $input->getOption('user');
        if (!$userPrompt) {
            $question = new Question(
                'Please enter the user prompt',
                'Please give an example with a random value.'
            );

            $userPrompt = $helper->ask($input, $output, $question);
        }
        $model = (string) $input->getOption('model');
        if (!$model) {
            $question = new Question(
                'Please enter a llm model name',
                'tinyllama'
            );

            $model = $helper->ask($input, $output, $question);
        }
        $type = (string) $input->getOption('type');
        if (!$type) {
            $question = new Question(
                'Please enter the PHP typehint you want to instruct',
                EntityInterface::class,
            );

            $type = $helper->ask($input, $output, $question);
        }
        $response = $this->aiInstructor->instruct(
            ReflectionTypeFactory::createReflectionType($type),
            NonEmptyString::fromNative($model),
            $systemPrompt,
            $userPrompt
        );
        dump($response);

        return Command::SUCCESS;
    }
}
