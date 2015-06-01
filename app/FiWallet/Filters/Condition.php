<?php

namespace FiWallet\Filters;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Kdyby\Doctrine\Entities\BaseEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="filters_conditions")
 *
 * @author Pavel Kouřil <pavel.kouril@hotmail.com>
 *
 * @property-read Filter $filter
 * @property string $property
 * @property string $operator
 * @property string $value
 */
class Condition extends BaseEntity
{
    const OPERATOR_GT = ">";
    const OPERATOR_GTE = ">=";
    const OPERATOR_EQ = "=";
    const OPERATOR_LT = "<";
    const OPERATOR_LTE = "<=";

    use Identifier;

    /**
     * @ORM\ManyToOne(targetEntity="Filter", inversedBy="conditions")
     * @var Filter
     */
    private $filter;

    /**
     * @ORM\Column()
     * @var string
     */
    private $property;

    /**
     * @ORM\Column()
     * @var string
     */
    private $operator;

    /**
     * @ORM\Column()
     * @var string
     */
    private $value;

    /**
     * @param Filter $filter
     * @param string $property
     * @param string $operator
     * @param string $value
     */
    public function __construct(Filter $filter, $property, $operator, $value)
    {
        parent::__construct();
        $this->filter = $filter;
        $this->property = $property;
        $this->operator = $operator;
        $this->value = $value;
    }

    /**
     * @return Filter
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @return string
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * @param string $property
     */
    public function setProperty($property)
    {
        $this->property = $property;
    }

    /**
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * @param string $operator
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'property' => $this->property,
            'operator' => $this->operator,
            'value' => $this->value
        ];
    }
}
