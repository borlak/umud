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

    /**
     * Get the block associated with the Area coordinates requested.
     * If the coordinates go beyond the bounds of the current blocks,
     * return false.
     * @return boolean|RoomBlock false if out of bounds
     */
    public function getBlock($x, $y) {
        if($x < 0 || $y < 0) {
            return false;
        }

        $block_x = (int)($x / BLOCK_SIZE);
        $block_y = (int)($y / BLOCK_SIZE);

        if($block_x > $max_blocks_x || $block_y > $max_blocks_y) {
            return false;
        }

        return $this->blocks[$block_x][$block_y];
    }

    public function getMap($center_x, $center_y, $radius_x, $radius_y) {
        $start_x = $center_x - $radius_x;
        $start_y = $center_y - $radius_y;
        $map = array();

        for($x = 0; $x < $radius_x*2+1; $x++) {
            $map[$x] = array();
            for($y = 0; $y < $radius_y*2+1; $y++) {
                $room = 0;
                $x_current = $start_x + $x;
                $y_current = $start_y + $y;

                if($x_current < 0 || $y_current < 0) {
                    $room = 0;
                } else {
                    $block_x = (int)($x_current / BLOCK_SIZE);
                    $block_y = (int)($y_current / BLOCK_SIZE);
                    if($block_x > $this->max_blocks_x || $block_y > $this->max_blocks_y) {
                        $room = 0;
                    } else {
                        $Block = $this->blocks[$block_x][$block_y];
                        $Room = $Block->getRoom(
                            $x_current-(BLOCK_SIZE*$block_x),
                            $y_current-(BLOCK_SIZE*$block_y)
                        );
                        $room = $Room->getType();
                    }
                }
                $map[$x][$y] = $room;
            }
        }

        return $map;
    }

    public function drawAsciiMap($map) {
        for($y = 0; $y < count($map); $y++) {
            for($x = 0; $x < count($map[$y]); $x++) {
                echo sprintf("%2d",$map[$x][$y]);
            }
            echo PHP_EOL;
        }
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
