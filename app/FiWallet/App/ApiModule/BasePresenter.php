<?php

namespace FiWallet\App\ApiModule;

use Drahak\Restful\Application\UI\ResourcePresenter;
use Drahak\Restful\IResource;
use Kdyby\Doctrine\EntityManager;

/**
 * @author Pavel Kouřil <pavel.kouril@hotmail.com>
 */
abstract class BasePresenter extends ResourcePresenter
{
    /**
     * @inject
     * @var EntityManager
     */
    public $entityManager;

    protected function startup()
    {
        parent::startup();
        if (!$this->user->loggedIn) {
            $this->sendError(401, 'Unauthorized');
        }
    }

    /**
     * Sends JSON error response for current request.
     *
     * @param int $code
     * @param string $message
     */
    protected function sendError($code, $message)
    {
        $this->sendErrorResource(new \Exception($message, $code), IResource::JSON);
    }

    /**
     * Sends data in $this->resource in JSON format.
     */
    protected function sendData()
    {
        $this->sendResource(IResource::JSON);
    }
}
