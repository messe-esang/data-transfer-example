<?php

namespace MesseEsang\App;

use JsonSerializable;

class Fair implements JsonSerializable
{
    public string $name;

    public \DateTime $startDate;

    public \DateTime $endDate;

    /**
     * 
     * @var string[]
     */
    public array $keywords;

    public function __construct(string $name, \DateTime $startDate, \DateTime $endDate, array $keywords = [])
    {
        $this->name = $name;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->keywords = $keywords;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'name' => $this->name,
            'startDate' => $this->startDate->format('Y-m-d'),
            'endDate' => $this->endDate->format('Y-m-d'),
            'keywords' => $this->keywords,
        ];
    }
}
