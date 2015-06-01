<?php

namespace FiWallet\Filters;

use Kdyby\Doctrine\EntityRepository;
use Nette\Object;
use Kdyby\Doctrine\EntityManager;
use FiWallet\Users\User;

/**
 * @author Adam Studenic>
 * @author Pavel Kouřil <pavel.kouril@hotmail.com>
 */
class FilterManager extends Object
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var EntityRepository
     */
    private $filterRepository;

    /**
     * @var EntityRepository
     */
    private $conditionRepository;

    public function __construct(EntityManager $entityManager)
    {
        $this->filterRepository = $entityManager->getRepository(Filter::class);
        $this->conditionRepository = $entityManager->getRepository(Condition::class);
        $this->entityManager = $entityManager;
    }

    /**
     * @param int $id
     *
     * @return Filter|null
     */
    public function find($id)
    {
        return $this->filterRepository->find($id);
    }

    /**
     * @param User $user
     * @param $name
     * @param $conditionData
     *
     * @return CustomFilter
     *
     */
    public function createFilter(User $user, $name, $conditionData)
    {
        $filter = new CustomFilter($user, $name, $conditionData);
        $this->entityManager->persist($filter);
        $this->entityManager->flush();
        return $filter;
    }

    /**
     * @param CustomFilter $filter
     * @param string $name
     * @param array[] $oldConditionData
     * @param array[]|null $newConditionData
     */
    public function editFilter(CustomFilter $filter, $name, $oldConditionData, $newConditionData = null)
    {
        $filter->name = $name;
        foreach ($oldConditionData as $id => $data) {
            $this->updateCondition($id, $data);
        }
        if ($newConditionData !== null) {
            $cond = new Condition($filter, $newConditionData['property'], $newConditionData['operator'], $newConditionData['value']);
            $this->entityManager->persist($cond);
            $filter->addCondition($cond);
        }
        $this->entityManager->flush();
    }

    /**
     * @param int $conditionId
     *
     * @return bool
     */
    public function deleteCondition($conditionId)
    {
        if ($condition = $this->findCondition($conditionId)) {
            if ($condition->filter->conditions->count() > 1) {
                $this->entityManager->remove($condition);
                $this->entityManager->flush($condition);
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * @param int $conditionId
     *
     * @return Condition|null
     */
    public function findCondition($conditionId)
    {
        return $this->conditionRepository->find($conditionId);
    }

    private function updateCondition($id, $data)
    {
        if ($condition = $this->findCondition($id)) {
            $condition->property = $data['property'];
            $condition->operator = $data['operator'];
            $condition->value = $data['value'];
        }
    }
}
