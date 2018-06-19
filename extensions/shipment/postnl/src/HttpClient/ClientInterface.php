<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2017-2018 Thirty Development, LLC
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and
 * associated documentation files (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge, publish, distribute,
 * sublicense, and/or sell copies of the Software, and to permit persons to whom the Software
 * is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or
 * substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT
 * NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * @author    Michael Dekker <michael@thirtybees.com>
 * @copyright 2017-2018 Thirty Development, LLC
 * @license   https://opensource.org/licenses/MIT The MIT License
 */

namespace ThirtyBees\PostNL\HttpClient;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Psr\Log\LoggerInterface;
use ThirtyBees\PostNL\Exception\HttpClientException;

/**
 * Interface ClientInterface
 *
 * @package ThirtyBees\PostNL\HttpClient
 */
interface ClientInterface
{
    /**
     * Set the logger
     *
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger);

    /**
     * Get the HTTP Client instance
     *
     * @return static
     */
    public static function getInstance();

    /**
     * Adds a request to the list of pending requests
     * Using the ID you can replace a request
     *
     * @param string $id      Request ID
     * @param string $request PSR-7 request
     *
     * @return int|string
     */
    public function addOrUpdateRequest($id, $request);

    /**
     * Set the verify setting
     *
     * @param bool|string $verify
     *
     * @return $this
     */
    public function setVerify($verify);

    /**
     * Return verify setting
     *
     * @return bool|string
     */
    public function getVerify();

    /**
     * Remove a request from the list of pending requests
     *
     * @param string $id
     */
    public function removeRequest($id);

    /**
     * Clear all requests
     */
    public function clearRequests();

    /**
     * Do a single request
     *
     * Exceptions are captured into the result array
     *
     * @param Request $request
     *
     * @return Response
     */
    public function doRequest(Request $request);

    /**
     * Do all async requests
     *
     * Exceptions are captured into the result array
     *
     * @param Request[] $requests
     *
     * @return Response|Response[]|HttpClientException|HttpClientException[]
     */
    public function doRequests($requests = []);
}
