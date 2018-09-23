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
    private $max_x;
    private $max_y;

    public function __construct($area, $room_x=25, $room_y=25) {
        $this->max_x = $room_x;
        $this->max_y = $room_y;
        $this->area = $area;
        $this->rooms = array();
        for($x = 0; $x < $room_x; $x++) {
            $this->rooms[$x] = array();
            for($y = 0; $y < $room_y; $y++) {
                $this->rooms[$x][$y] = new Room($this);
            }
        }
    }

    /**
     * Return # of rooms in this block.
     * @return int
     */
    public function roomCount() {
        return $this->max_x * $this->max_y;
    }
}
