<?php


class Events
{
    private int $id;
    private string $name;
    private int $price;
    private string $start;
    private string $dag;
    private string $maand;
    private string $end;
    private string $description;
    private string $location;

    public function __construct(int $id, string $name, int $price, string $start, string $dag, string $maand, string $end, string $description, string $location)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->dag = $dag;
        $this->maand = $maand;
        $this->start = $start;
        $this->end = $end;
        $this->description = $description;
        $this->location = $location;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int|string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param int|string $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return float|int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float|int $price
     */
    public function setPrice($price): void
    {
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getStart(): string
    {
        return $this->start;
    }

    /**
     * @param string $start
     */
    public function setStart(string $start): void
    {
        $this->start = $start;
    }

    /**
     * @return string
     */
    public function getDag(): string
    {
        return $this->dag;
    }

    /**
     * @param string $dag
     */
    public function setDag(string $dag): void
    {
        $this->dag = $dag;
    }

    public function getMaand(): string
    {
        return $this->maand;
    }

    public function setMaand(string $maand): void
    {
        $this->maand = $maand;
    }

    /**
     * @return string
     */
    public function getEnd(): string
    {
        return $this->end;
    }

    /**
     * @param string $end
     */
    public function setEnd(string $end): void
    {
        $this->end = $end;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * @param string $location
     */
    public function setLocation(string $location): void
    {
        $this->location = $location;
    }

    public function __toString(): string
    {
        return $this->id . ' ' . $this->name . ' ' . $this->price . ' ' . $this->start . ' ' . $this->dag . ' ' . $this->end . ' ' . $this->description . ' ' . $this->location;
    }

}