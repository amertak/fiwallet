<?php

namespace FiWallet\Filters;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Kdyby\Doctrine\Entities\BaseEntity;

/**
 * @ORM\Entity()
 * @ORM\Table(name="filters")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"system"="SystemFilter", "custom"="CustomFilter"})
 *
 * @author Pavel Kouřil <pavel.kouril@hotmail.com>
 *
 * @property-read Condition[]|ArrayCollection $conditions
 * @property string $name
 */
abstract class Filter extends BaseEntity
{
    use Identifier;

    /**
     * @ORM\Column()
     * @var string
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Condition", mappedBy="filter", cascade={"persist"})
     * @var string
     */
    private $conditions;

    /**
     * @param string $name
     * @param array[] $conditionData
     *
     * @throws \Exception
     */
    public function __construct($name, $conditionData)
    {
        parent::__construct();
        if (count($conditionData) == 0) {
            throw new \Exception("Can't create Filter with no conditions.");
        }
        $this->conditions = new ArrayCollection();
        foreach ($conditionData as $cond) {
            $this->addCondition(new Condition($this, $cond['property'], $cond['operator'], $cond['value']));
        }
        $this->name = $name;
    }

    public function getConditions()
    {
        return $this->conditions;
    }

    public function addCondition(Condition $condition)
    {
        $this->conditions->add($condition);
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'conditions' => array_map(
                function (Condition $condition) {
                    return $condition->toArray();
                },
                $this->conditions->toArray()
            )
        ];
    }
}
