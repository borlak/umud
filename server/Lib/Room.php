<?php
class Room {
    /**
     * @var RoomBlock
     */
    private $block;

    public function __construct($block) {
        $this->block = $block;
    }
}
