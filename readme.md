# GitHub Archive Installer

A custom Composer installer that will install a dependency from a GitHub release archive .zip file when installing from distribution.

## Why You Need It
It is very common that you might have a number of development only files in your code library source files. Unless explicitly installing from source, you generally don't need to have development only files in your final distribution. You may also want to perform some specific build steps such as building some production-ready JavaScript files. This installer allows you to keep generated code out of your repository while still being able to reliably deliver it in your final distribution.

Using a tool like Travis CI, you can automate the build of your final distribution and automatically attach the generated .zip file to the release on GitHub. Then, using this installer, you can easily configure your library to install the generated .zip file when installing as `dist` in Composer.

## How it Works

Any package with a direct dependency on `wpscholar/github-archive-installer` and a valid stable version number being installed from distribution will be installed from the GitHub archive `<repo-name>.zip` file associated with a specific release.

For example, if my Composer package is named `wpscholar/hello-world` then my generated .zip file should be named `hello-world.zip` in order to be properly installed using this installer.

This installer only changes the `distUrl` for the package in Composer. It doesn't override any existing installers that work based off of the `type` property in your `composer.json` file. For example, if you have a package of type `wordpress-plugin` your package would still install in the correct location in WordPress. However, when installing from `dist` it would simply pull from the .zip file attached to your GitHub release.
