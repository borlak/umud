<?php
// Contains blocks of rooms that make up the map or area
class Area {
    private $name;
    /**
     * @var RoomBlock[]
     */
    private $blocks;
    private $max_blocks_x;
    private $max_blocks_y;

    public function __construct($name = "New Area", $blocks_x = 5, $blocks_y = 5) {
        $this->max_blocks_x = $blocks_x;
        $this->max_blocks_y = $blocks_y;
        $this->name = $name;
        $this->blocks = array();
        for($x = 0; $x < $blocks_x; $x++) {
            $this->blocks[$x] = array();
            for($y = 0; $y < $blocks_y; $y++) {
                $this->blocks[$x][$y] = new RoomBlock($this);
            }
        }
    }

    public function getMap() {
    }

    /**
     * Return the total # of rooms in this Area.
     * @return int
     */
    public function roomCount() {
        $total = 0;
        for($x = 0; $x < $this->max_blocks_x; $x++) {
            for($y = 0; $y < $this->max_blocks_y; $y++) {
                $total += $this->blocks[$x][$y]->roomCount();
            }
        }
        return $total;
    }
}
