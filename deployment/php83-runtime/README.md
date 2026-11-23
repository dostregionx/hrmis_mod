# HRMIS PHP 8.3 runtime

This directory preserves the production runtime build inputs.

Build separately from the live service:

    docker build -t hrmis-runtime:php83-reviewed deployment/php83-runtime

The image contains PHP, Apache, required extensions and the upload guard.
Application code is supplied through the /srv/hrmis/html bind mount.
Production PHP security settings are supplied through a separate read-only mount.

The production Compose files remain in /srv/hrmis, outside this repository.
Do not replace them with test configuration.
Changing or building an image does not automatically update the running container.

Application dependencies are tracked in vendor. Production dependencies were
installed from composer.lock with --no-dev --no-scripts --no-plugins.
The dotenv Loader.php includes a PHP 8.3 compatibility patch for line endings.
A future Composer install can overwrite that patch; review and reapply it
before deployment.

Keep production database settings, .env and uploads outside runtime images.
Do not copy isolated-test email bypasses or database settings into production.
