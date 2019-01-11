<?php
declare(strict_types=1);

namespace App;

class PlatformShEnv
{
    private const APPLICATION = 'PLATFORM_APPLICATION';
    private const RELATIONSHIPS = 'PLATFORM_RELATIONSHIPS';
    private const PROJECT_ENTROPY = 'PLATFORM_PROJECT_ENTROPY';

    public static function setEnvs(): void
    {
        // If this env var is not set then we're not on a Platform.sh
        // environment or in the build hook, so don't try to do anything.
        if (!getenv(''.self::APPLICATION)) {
            return;
        }

        // Set the application secret if it's not already set.
        $secret = getenv('APP_SECRET') ?: getenv(self::PROJECT_ENTROPY) ?: null;
        self::setEnvVar('APP_SECRET', $secret);

        $relationships = getenv(self::RELATIONSHIPS);
        if ($relationships && $servicesJson = base64_decode($relationships, true)) {
            $services = json_decode($servicesJson, true);

            if (array_key_exists('solr', $services)) {
                self::handleSolrService($services['solr']);
            }
            if (array_key_exists('rabbitmq', $services)) {
                self::handleRabbitMq($services['rabbitmq']);
            }
        }
    }

    private static function handleSolrService(array $solrServices): void
    {
        $solr = current($solrServices);

        self::setEnvVar('SOLR_HOST', (string) $solr['host']);
        self::setEnvVar('SOLR_PORT', (string) $solr['port']);

        $core = \str_replace('solr/', '', $solr['path']);
        self::setEnvVar('SOLR_CORE', (string) $core);
    }

    private static function handleRabbitMq(array $rabbitServices) {
        $rabbitService = current($rabbitServices);
        $rabbitDsn = sprintf('%s://%s:%s@%s:%s/%%2f/messages',
            $rabbitService['scheme'],
            $rabbitService['username'],
            $rabbitService['password'],
            $rabbitService['host'],
            $rabbitService['port']
        );
        self::setEnvVar('MESSENGER_TRANSPORT_DSN', $rabbitDsn);
    }

    /**
     * Sets an environment variable in all the myriad places PHP can store it.
     *
     * @param string      $name  The name of the variable to set.
     * @param null|string $value The value to set.  Null to unset it.
     */
    private static function setEnvVar(string $name, ?string $value): void
    {
        if (!putenv("$name=$value")) {
            throw new \RuntimeException('Failed to create environment variable: ' . $name);
        }
        $order = ini_get('variables_order');
        if (stripos($order, 'e') !== false) {
            $_ENV[$name] = $value;
        }
        if (stripos($order, 's') !== false) {
            if (strpos($name, 'HTTP_') !== false) {
                throw new \RuntimeException('Refusing to add ambiguous environment variable ' . $name . ' to $_SERVER');
            }
            $_SERVER[$name] = $value;
        }
    }
}
