<?php


class Tickets
{
    private int $id;
    private string $ticketName;
    private float $ticketPrice;
    private string $reason;
    private int $eventId;
    private string $soort;
    private int $sellerID;

    public function __construct(int $id, string $ticketName, float $ticketPrice, string $reason, int $eventId, string $soort, int $sellerID){
        $this->id = $id;
        $this->ticketName = $ticketName;
        $this->ticketPrice = $ticketPrice;
        $this->reason = $reason;
        $this->eventId = $eventId;
        $this->soort = $soort;
        $this->sellerID = $sellerID;
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
     * @return string
     */
    public function getTicketName(): string
    {
        return $this->ticketName;
    }

    /**
     * @param string $ticketName
     */
    public function setTicketName(string $ticketName): void
    {
        $this->ticketName = $ticketName;
    }

    /**
     * @return int
     */
    public function getTicketPrice(): int
    {
        return $this->ticketPrice;
    }

    /**
     * @param int $ticketPrice
     */
    public function setTicketPrice(int $ticketPrice): void
    {
        $this->ticketPrice = $ticketPrice;
    }

    /**
     * @return string
     */
    public function getReason(): string
    {
        return $this->reason;
    }

    /**
     * @param string $reason
     */
    public function setReason(string $reason): void
    {
        $this->reason = $reason;
    }

    /**
     * @return int
     */
    public function getEventId(): int
    {
        return $this->eventId;
    }

    /**
     * @param int $eventId
     */
    public function setEventId(int $eventId): void
    {
        $this->eventId = $eventId;
    }

    /**
     * @return string
     */
    public function getSoort(): string
    {
        return $this->soort;
    }

    /**
     * @param string $soort
     */
    public function setSoort(string $soort): void
    {
        $this->soort = $soort;
    }

    public function getSellerID(){
        return $this->sellerID;
    }

    public function setSellerID(int $sellerID): void{
        $this->sellerID = $sellerID;
    }

    public function __toString(): string{
        return $this->id . ' ' . $this->ticketName . ' ' . $this->ticketPrice . ' ' .$this->reason . ' ' . $this->eventId . ' ' . $this->reason;
    }
}