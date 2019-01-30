<?php

namespace Wearesho\Evrotel;

use Carbon\Carbon;

/**
 * Class Receiver
 * @package Wearesho\Evrotel
 */
class Receiver
{
    /** @var ConfigInterface */
    protected $config;

    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * @return Receiver\Request
     * @throws Exceptions\AccessDenied
     * @throws Exceptions\BadRequest
     */
    public function getRequest(): Receiver\Request
    {
        $authorization = $_SERVER['HTTP_AUTHORIZATION'] ?? null;
        if (is_null($authorization) || $authorization !== $this->config->getToken()) {
            throw new Exceptions\AccessDenied();
        }

        $callStatus = $_POST['callstatus'] ?? null;
        if (is_null($callStatus)) {
            throw new Exceptions\BadRequest("Missing callstatus");
        }

        switch ($callStatus) {
            case Call\Status::START:
                return $this->getStartRequest();
            case Call\Status::END:
                return $this->getEndRequest();
        }

        throw new Exceptions\BadRequest("Invalid callstatus");
    }

    /**
     * @return Receiver\Request\Start
     * @throws Exceptions\BadRequest
     */
    protected function getStartRequest(): Receiver\Request\Start
    {
        try {
            $date = isset($_POST['date']) || \array_key_exists('date', $_POST)
                ? Carbon::createFromFormat('Y-m-d H:i:s', $_POST['date'])
                : null;

            return new Receiver\Request\Start(
                $_POST['direction'] ?? null,
                $date,
                $_POST['numberA'] ?? null,
                $_POST['numberB'] ?? null
            );
        } catch (\TypeError $error) {
            throw new Exceptions\BadRequest("Invalid argument type", 2, $error);
        } catch (\InvalidArgumentException $exception) {
            throw new Exceptions\BadRequest($exception->getMessage(), 1, $exception);
        }
    }

    /**
     * @return Receiver\Request\End
     * @throws Exceptions\BadRequest
     */
    protected function getEndRequest(): Receiver\Request\End
    {
        try {
            $date = isset($_POST['date']) || \array_key_exists('date', $_POST)
                ? Carbon::createFromFormat('Y-m-d H:i:s', $_POST['date'])
                : null;

            /** @noinspection PhpUnhandledExceptionInspection */
            $duration = isset($_POST['billsec']) || \array_key_exists('billsec', $_POST)
                ? new \DateInterval('PT' . ((int)$_POST['billsec']) . 'S')
                : null;

            return new Receiver\Request\End(
                $_POST['direction'] ?? null,
                $date,
                $_POST['disposition'] ?? null,
                $_POST['callid'] ?? null,
                $duration,
                $_POST['recfile'] ?? null
            );
        } catch (\TypeError $error) {
            throw new Exceptions\BadRequest("Invalid argument type", 2, $error);
        } catch (\InvalidArgumentException $exception) {
            throw new Exceptions\BadRequest($exception->getMessage(), 1, $exception);
        }
    }
}
