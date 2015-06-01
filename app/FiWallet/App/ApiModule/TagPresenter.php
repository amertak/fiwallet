<?php

namespace FiWallet\App\ApiModule;

use FiWallet\Transactions\Tag;
use Kdyby\Doctrine\EntityRepository;

/**
 * @author Pavel Kouřil <pavel.kouril@hotmail.com>
 */
class TagPresenter extends BasePresenter
{
    /**
     * @var EntityRepository
     */
    private $tagRepository;

    protected function startup()
    {
        parent::startup();
        $this->tagRepository = $this->entityManager->getRepository(Tag::class);
    }

    /**
     * @GET tags
     */
    public function actionContent()
    {
        $this->resource->data = array_map(function (Tag $t) {
            return $t->toArray();
        }, $this->tagRepository->findBy(['user' => $this->user->identity]));
        $this->sendData();
    }
}
