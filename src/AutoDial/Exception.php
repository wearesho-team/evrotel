<?php

namespace Wearesho\Evrotel\AutoDial;

use Psr;

/**
 * Class Exception
 * @package Wearesho\Evrotel\AutoDial
 */
class Exception extends \Exception
{
    /** @var RequestInterface */
    protected $request;

    /** @var Psr\Http\Message\ResponseInterface */
    protected $response;

    public function __construct(
        RequestInterface $request,
        Psr\Http\Message\ResponseInterface $response,
        int $code,
        \Throwable $previous = null
    ) {
        $message = "Error while dialing to {$request->getPhone()} using file {$request->getFileName()}."
            . " Response: " . $response->getBody();
        parent::__construct($message, $code, $previous);

        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @return Psr\Http\Message\ResponseInterface
     */
    public function getResponse(): Psr\Http\Message\ResponseInterface
    {
        return $this->response;
    }

    /**
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }
}
