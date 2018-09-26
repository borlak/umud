<?php
class Room {
    /**
     * @var Mobile[]
     */
    private $mobiles = array();
    /**
     * @var Object[]
     */
    private $objects = array();
    private $type = 0;

    public function __construct() {
        $this->type = rand(1,99);
    }

    public function getType() {
        return $this->type;
    }
}
