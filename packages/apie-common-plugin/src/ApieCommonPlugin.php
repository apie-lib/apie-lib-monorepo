<?php
namespace Apie\ApieCommonPlugin;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

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

        $generator = new AvailableApieObjectProviderGenerator();
        $generator->generateFile($classNames);
        @chmod(__DIR__ . '/AvailableApieObjectProvider.php', 0666);
    }
}
