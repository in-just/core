<?php

/*
 * This file is part of Flarum.
 *
 * For detailed copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace Flarum\Api;

use Exception;
use Throwable;
use Tobscure\JsonApi\Document;
use Tobscure\JsonApi\ErrorHandler as JsonApiErrorHandler;

class ErrorHandler
{
    /**
     * @var JsonApiErrorHandler
     */
    protected $errorHandler;

    /**
     * @param JsonApiErrorHandler $errorHandler
     */
    public function __construct(JsonApiErrorHandler $errorHandler)
    {
        $this->errorHandler = $errorHandler;
    }

    /**
     * @param Exception $e
     * @return JsonApiResponse
     */
    public function handle(Throwable $e)
    {
        if (! $e instanceof Exception) {
            $e = new Exception($e->getMessage(), $e->getCode(), $e);
        }

        $response = $this->errorHandler->handle($e);

        $document = new Document;
        $document->setErrors($response->getErrors());

        return new JsonApiResponse($document, $response->getStatus());
    }
}