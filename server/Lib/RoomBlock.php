<?php
// This is a block which contains x by x rooms
class RoomBlock {
    /**
     * @var Area
     */
    private $area;
    /**
     * @var Room[]
     */
    private $rooms;
    /**
     * How big this block is for each axis (x and y).  Blocks have to be square.
     * @var int
     */
    private $block_size = BLOCK_SIZE;

    public function __construct($area, $block_size=25) {
        $this->block_size = $block_size;
        $this->area = $area;
        $this->rooms = array();
        for($x = 0; $x < $block_size; $x++) {
            $this->rooms[$x] = array();
            for($y = 0; $y < $block_size; $y++) {
                $this->rooms[$x][$y] = new Room();
            }
        }
    }

    /**
     * Return a specific room within the block.
     * @param int $x
     * @param int $y
     * @return Room
     */
    public function getRoom($x, $y) {
        return $this->rooms[$x][$y];
    }

    /**
     * Return # of rooms in this block.
     * @return int
     */
    public function roomCount() {
        return BLOCK_SIZE*BLOCK_SIZE;
    }
}
