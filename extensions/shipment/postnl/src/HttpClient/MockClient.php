<?php
/**
 * Copyright (C) 2017 thirty bees
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.md
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@thirtybees.com so we can send you a copy immediately.
 *
 * @author    thirty bees <modules@thirtybees.com>
 * @copyright 2017-2018 thirty bees
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

namespace ThirtyBees\PostNL\HttpClient;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use ThirtyBees\PostNL\Exception\HttpClientException;

/**
 * Class MockClient
 *
 * @package ThirtyBees\PostNL\HttpClient
 */
class MockClient implements ClientInterface, LoggerAwareInterface
{
    const DEFAULT_TIMEOUT = 60;
    const DEFAULT_CONNECT_TIMEOUT = 20;

    /** @var static $instance */
    protected static $instance;
    /** @var array $defaultOptions */
    protected $defaultOptions = [];
    /**
     * List of pending PSR-7 requests
     *
     * @var Request[]
     */
    protected $pendingRequests = [];
    /** @var LoggerInterface $logger */
    protected $logger;
    /** @var int $timeout */
    private $timeout = self::DEFAULT_TIMEOUT;
    /** @var int $connectTimeout */
    private $connectTimeout = self::DEFAULT_CONNECT_TIMEOUT;
    /** @var HandlerStack $handler */
    private $handler;

    /** @var int $maxRetries */
    private $maxRetries = 1;

    /**
     * @return MockClient|static
     */
    public static function getInstance()
    {
        if (!static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Set Guzzle option
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return MockClient
     */
    public function setOption($name, $value) {
        $this->defaultOptions[$name] = $value;

        return $this;
    }

    /**
     * Get Guzzle option
     *
     * @param string $name
     *
     * @return mixed|null
     */
    public function getOption($name)
    {
        if (isset($this->defaultOptions[$name])) {
            return $this->defaultOptions[$name];
        }

        return null;
    }

    /**
     * Set the verify setting
     *
     * @param bool|string $verify
     *
     * @return $this
     */
    public function setVerify($verify)
    {
        $this->defaultOptions['verify'] = $verify;

        return $this;
    }

    /**
     * Return verify setting
     *
     * @return bool|string
     */
    public function getVerify()
    {
        if (isset($this->defaultOptions['verify'])) {
            return $this->defaultOptions['verify'];
        }

        return false;
    }

    /**
     * Set the amount of retries
     *
     * @param int $maxRetries
     *
     * @return $this
     */
    public function setMaxRetries($maxRetries)
    {
        $this->maxRetries = $maxRetries;

        return $this;
    }

    /**
     * Return max retries
     *
     * @return int
     */
    public function getMaxRetries()
    {
        return $this->maxRetries;
    }

    /**
     * Set the logger
     *
     * @param LoggerInterface $logger
     *
     * @return MockClient
     */
    public function setLogger(LoggerInterface $logger = null)
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * Get the logger
     *
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * Adds a request to the list of pending requests
     * Using the ID you can replace a request
     *
     * @param string $id      Request ID
     * @param string $request PSR-7 request
     *
     * @return int|string
     */
    public function addOrUpdateRequest($id, $request)
    {
        if (is_null($id)) {
            return array_push($this->pendingRequests, $request);
        }

        $this->pendingRequests[$id] = $request;

        return $id;
    }

    /**
     * Remove a request from the list of pending requests
     *
     * @param string $id
     */
    public function removeRequest($id)
    {
        unset($this->pendingRequests[$id]);
    }

    /**
     * Clear all pending requests
     */
    public function clearRequests()
    {
        $this->pendingRequests = [];
    }

    /**
     * @return MockClient
     */
    public function setHandler(HandlerStack $handler)
    {
        $this->handler = $handler;

        return $this;
    }

    /**
     * @return HandlerStack
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * Do a single request
     *
     * Exceptions are captured into the result array
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws HttpClientException
     */
    public function doRequest(Request $request)
    {
        // Initialize Guzzle, include the default options
        $guzzle = new Client(array_merge(
            $this->defaultOptions,
            [
                'timeout'         => $this->timeout,
                'connect_timeout' => $this->connectTimeout,
                'http_errors'     => false,
                'handler'         => $this->handler,
            ]
        ));

        try {
            return $guzzle->send($request);
        } catch (GuzzleException $e) {
            throw new HttpClientException(null, null, $e);
        }
    }

    /**
     * Do all async requests
     *
     * Exceptions are captured into the result array
     *
     * @param Request[] $requests
     *
     * @return Response|Response[]|HttpClientException|HttpClientException[]
     */
    public function doRequests($requests = [])
    {
        // If this is a single request, create the requests array
        if (!is_array($requests)) {
            if (!$requests instanceof Request) {
                return [];
            }

            $requests = [$requests];
        }

        // Handle pending requests
        $requests = $this->pendingRequests + $requests;
        $this->clearRequests();

        // Initialize Guzzle and the retry middleware, include the default options
        $guzzle = new Client(array_merge(
            $this->defaultOptions,
            [
                'timeout'         => $this->timeout,
                'connect_timeout' => $this->connectTimeout,
                'handler'         => $this->handler,
            ]
        ));

        // Concurrent requests
        $promises = [];
        foreach ($requests as $index => $request) {
            $promises[$index] = $guzzle->sendAsync($request);
        }

        $responses = \GuzzleHttp\Promise\settle($promises)->wait();
        foreach ($responses as &$response) {
            if (isset($response['value'])) {
                $response = $response['value'];
            } elseif (isset($response['reason'])) {
                $response = $response['reason'];
            } else {
                $response = \ThirtyBees\PostNL\Exception\ResponseException('Unknown reponse type');
            }
            if ($response instanceof Response && $this->logger instanceof LoggerInterface) {
                $this->logger->debug(\GuzzleHttp\Psr7\str($response));
            }
        }

        return $responses;
    }
}
