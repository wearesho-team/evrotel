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

        $callStatus = $_POST['callstatus'];
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
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $_POST['date']);

            return new Receiver\Request\Start(
                $_POST['direction'],
                $date,
                $_POST['numberA'],
                $_POST['numberB']
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
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $_POST['date']);
            /** @noinspection PhpUnhandledExceptionInspection */
            $duration = new \DateInterval('PT' . ((int)$_POST['billsec']) . 'S');

            return new Receiver\Request\End(
                $_POST['direction'],
                $date,
                $_POST['disposition'],
                $_POST['callid'],
                $duration,
                $_POST['recfile']

            );
        } catch (\TypeError $error) {
            throw new Exceptions\BadRequest("Invalid argument type", 2, $error);
        } catch (\InvalidArgumentException $exception) {
            throw new Exceptions\BadRequest($exception->getMessage(), 1, $exception);
        }
    }
}
