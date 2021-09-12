<?php
namespace Tustin\PlayStation\Factory;

use Iterator;
use IteratorAggregate;
use Tustin\PlayStation\Api;
use Tustin\PlayStation\Enum\TrophyType;
use Tustin\PlayStation\Model\TrophyGroup;
use Tustin\PlayStation\AbstractTrophyTitle;
use Tustin\PlayStation\Interfaces\FactoryInterface;
use Tustin\PlayStation\Iterator\TrophyGroupsIterator;
use Tustin\PlayStation\Iterator\Filter\TrophyGroup\NameFilter;
use Tustin\PlayStation\Iterator\Filter\TrophyGroup\DetailFilter;
use Tustin\PlayStation\Iterator\Filter\TrophyGroup\TrophyTypeFilter;

class TrophyGroupsFactory extends Api implements IteratorAggregate, FactoryInterface
{
    /**
     * The trophy groups' title.
     *
     * @var AbstractTrophyTitle
     */
    private $title;

    private $withName = '';
    private $withDetail = '';

    private $certainTrophyTypeFilter = [];

    public function __construct(AbstractTrophyTitle $title)
    {
        $this->title = $title;
    }

    public function withName(string $name)
    {
        $this->withName = $name;

        return $this;
    }

    public function withDetail(string $detail)
    {
        $this->withDetail = $detail;
        
        return $this;
    }

    public function withTrophyCount(TrophyType $trophy, string $operand, int $count)
    {
        $this->certainTrophyTypeFilter[] = [$trophy, $operand, $count];

        return $this;
    }

    public function withTotalTrophyCount(string $operand, int $count)
    {
        // 
    }

    /**
     * Gets the iterator and applies any filters.
     *
     * @return Iterator
     */
    public function getIterator() : Iterator
    {
        $iterator = new TrophyGroupsIterator($this->title);

        if ($this->withName)
        {
            $iterator = new NameFilter($iterator, $this->withName);
        }

        if ($this->withDetail)
        {
            $iterator = new DetailFilter($iterator, $this->withDetail);
        }

        if ($this->certainTrophyTypeFilter)
        {
            foreach ($this->certainTrophyTypeFilter as $filter)
            {
                $iterator = new TrophyTypeFilter($iterator, ...$filter);
            }
        }

        return $iterator;
    }

    /**
     * Gets the first trophy title in the collection.
     *
     * @return TrophyGroup
     */
    public function first() : TrophyGroup
    {
        return $this->getIterator()->current();
    }
}