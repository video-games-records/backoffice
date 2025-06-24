<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test suite for health check endpoints
 */
class HealthCheckControllerTest extends WebTestCase
{
    public function testBasicHealthCheck(): void
    {
        $client = static::createClient();
        $client->request('GET', '/health');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('timestamp', $response);
        $this->assertArrayHasKey('environment', $response);
        $this->assertArrayHasKey('version', $response);

        $this->assertEquals('ok', $response['status']);
        $this->assertEquals('test', $response['environment']);
    }

    public function testDetailedHealthCheck(): void
    {
        $client = static::createClient();
        $client->request('GET', '/health/detailed');

        $this->assertResponseIsSuccessful();

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('checks', $response);

        // Check that all expected health checks are present
        $expectedChecks = ['database', 'cache', 'disk_space', 'log_directory', 'memory'];
        foreach ($expectedChecks as $checkName) {
            $this->assertArrayHasKey($checkName, $response['checks']);
            $this->assertArrayHasKey('status', $response['checks'][$checkName]);
            $this->assertArrayHasKey('message', $response['checks'][$checkName]);
        }
    }

    public function testReadinessProbe(): void
    {
        $client = static::createClient();
        $client->request('GET', '/health/ready');

        $this->assertResponseIsSuccessful();

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('checks', $response);
        $this->assertEquals('ready', $response['status']);
    }

    public function testLivenessProbe(): void
    {
        $client = static::createClient();
        $client->request('GET', '/health/live');

        $this->assertResponseIsSuccessful();

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('timestamp', $response);
        $this->assertEquals('alive', $response['status']);
    }

    public function testHealthCheckResponseFormat(): void
    {
        $client = static::createClient();
        $client->request('GET', '/health/detailed');

        $response = json_decode($client->getResponse()->getContent(), true);

        // Validate response structure
        $this->assertIsString($response['status']);
        $this->assertIsInt($response['timestamp']);
        $this->assertIsString($response['environment']);
        $this->assertIsString($response['version']);
        $this->assertIsArray($response['checks']);

        // Validate each check has required fields
        foreach ($response['checks'] as $checkName => $check) {
            $this->assertIsString($check['status']);
            $this->assertIsString($check['message']);
            $this->assertContains($check['status'], ['healthy', 'warning', 'error']);
        }
    }

    public function testHealthCheckAuthentication(): void
    {
        // Health checks should be accessible without authentication
        $client = static::createClient();

        $publicEndpoints = ['/health', '/health/live', '/health/ready'];

        foreach ($publicEndpoints as $endpoint) {
            $client->request('GET', $endpoint);
            $this->assertResponseIsSuccessful(sprintf('Endpoint %s should be accessible', $endpoint));
        }
    }
}
