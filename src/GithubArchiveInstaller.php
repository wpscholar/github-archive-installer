<?php
/**
 * Installs GitHub archive files from a release when installing from distribution.
 *
 * @package wpscholar/Composer/GithubArchiveInstaller
 */

namespace wpscholar\Composer;

use Composer\Composer;
use Composer\DependencyResolver\Operation\OperationInterface;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\PackageEvent;
use Composer\Installer\PackageEvents;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

/**
 * Class GithubArchiveInstaller
 *
 * @package wpscholar\Composer
 */
class GithubArchiveInstaller implements PluginInterface, EventSubscriberInterface {

	const PACKAGE_TYPE = 'github-archive-installer';

	/**
	 * Composer instance.
	 *
	 * @var \Composer\Composer
	 */
	protected $composer;

	/**
	 * Input/Output interface.
	 *
	 * @var \Composer\IO\IOInterface
	 */
	protected $io;

	/**
	 * Apply plugin modifications to Composer.
	 *
	 * @param \Composer\Composer       $composer Composer instance
	 * @param \Composer\IO\IOInterface $io       Input/Output interface
	 */
	public function activate( Composer $composer, IOInterface $io ) {
		$this->composer = $composer;
		$this->io       = $io;
	}

	/**
	 * Returns an array of event names this subscriber wants to listen to.
	 *
	 * @return array
	 */
	public static function getSubscribedEvents() {
		return array(
			PackageEvents::PRE_PACKAGE_INSTALL => 'preInstall',
			PackageEvents::PRE_PACKAGE_UPDATE  => 'preInstall',
		);
	}

	/**
	 * Set distribution URL before installing.
	 *
	 * @param \Composer\Installer\PackageEvent $event The package install or update event.
	 */
	public function preInstall( PackageEvent $event ) {

		/**
		 * Get the package instance.
		 *
		 * @var \Composer\Package\Package $package
		 */
		$package = $this->getPackageFromOperation( $event->getOperation() );

		if ( array_key_exists( 'wpscholar/github-archive-installer', $package->getRequires() ) ) {
			if ( version_compare( $package->getFullPrettyVersion(), '0.0.0', '>=' ) ) {
				$package->setDistUrl(
					sprintf(
						'https://github.com/%1$s/releases/download/%2$s/%3$s.zip',
						$package->getName(),
						$package->getFullPrettyVersion(),
						explode( '/', $package->getName() )[1]
					)
				);
			}
		}
	}

	/**
	 * Convert operation to package instance.
	 *
	 * @param \Composer\DependencyResolver\Operation\OperationInterface $operation The operation
	 *
	 * @return \Composer\Package\PackageInterface The package of the operation
	 */
	public function getPackageFromOperation( OperationInterface $operation ) {
		if ( 'update' === $operation->getJobType() ) {
			/**
			 * Operation is an update operation.
			 *
			 * @var \Composer\DependencyResolver\Operation\UpdateOperation $operation
			 */
			return $operation->getTargetPackage();
		}

		/**
		 * Operation is an install operation.
		 *
		 * @var \Composer\DependencyResolver\Operation\InstallOperation $operation
		 */
		return $operation->getPackage();
	}

	/**
	 * Deactivate
	 *
	 * @param \Composer\Composer       $composer Composer instance
	 * @param \Composer\IO\IOInterface $io       Input/Output interface
	 */
	public function deactivate( Composer $composer, IOInterface $io ) {
		// Nothing to do here.
	}

	/**
	 * Uninstall
	 *
	 * @param \Composer\Composer       $composer Composer instance
	 * @param \Composer\IO\IOInterface $io       Input/Output interface
	 */
	public function uninstall( Composer $composer, IOInterface $io ) {
		// Nothing to do here.
	}

}
