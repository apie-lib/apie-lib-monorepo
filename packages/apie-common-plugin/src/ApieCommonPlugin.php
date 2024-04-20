<?php
namespace Apie\ApieCommonPlugin;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Nette\PhpGenerator\PhpFile;

class ApieCommonPlugin implements PluginInterface, EventSubscriberInterface
{
    protected $composer;

    public function activate(Composer $composer, IOInterface $io)
    {
        $this->composer = $composer;
    }

    public function deactivate(Composer $composer, IOInterface $io): void
    {
    }

    public function uninstall(Composer $composer, IOInterface $io): void
    {
    }

    public static function getSubscribedEvents()
    {
        return [
            'post-install-cmd' => 'generatePhpCode',
            'post-update-cmd' => 'generatePhpCode',
        ];
    }

    public function generatePhpCode()
    {
        $installedRepo = $this->composer->getRepositoryManager()->getLocalRepository();
        $packages = $installedRepo->getPackages();

        $classNames = [];
        foreach ($packages as $package) {
            $extra = $package->getExtra();
            if (isset($extra['apie-objects']) && is_array($extra['apie-objects'])) {
                $classNames = [...$classNames, ...$extra['apie-objects']];
            }
        }

        $phpCode = $this->generateAvailableApieObjectProvider($classNames);
        file_put_contents(__DIR__ . '/AvailableApieObjectProvider.php', $phpCode);
        @chmod(__DIR__ . '/AvailableApieObjectProvider.php', 0666);
    }

    private function generateAvailableApieObjectProvider(array $classNames): string
    {
        $phpFile = new PhpFile();
        $namespace = $phpFile->addNamespace('Apie\ApieCommonPlugin');

        $namespace->addUse(ObjectProvider::class);

        $class = $namespace->addClass('AvailableApieObjectProvider');
        $class->setComment('@codeCoverageIgnore' . PHP_EOL . 'This class is auto-generated');

        $class->addMethod('__construct')->setPrivate();

        $class->setExtends(ObjectProvider::class);
        $class->addConstant('DEFINED_CLASSES', $classNames)->setVisibility('protected');
        
        return (string) $phpFile;
       
    }
}
