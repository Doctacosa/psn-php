<?php
namespace Tustin\PlayStation\Iterator\Filter\TrophyGroup;

use Iterator;
use FilterIterator;
use Tustin\PlayStation\Enum\TrophyType;
use Tustin\PlayStation\Traits\OperandParser;

class TrophyTypeFilter extends FilterIterator
{
    use OperandParser;

    private $trophyType;
    private $count;

    public function __construct(Iterator $iterator, TrophyType $trophyType, string $operator, int $count)
    {
        parent::__construct($iterator);
        $this->trophyType = $trophyType;
        $this->operator = $operator;
        $this->count = $count;
    }

    public function accept()
    {
        return $this->parse($this->current()->trophyCount($this->trophyType), $this->count);
    }
}