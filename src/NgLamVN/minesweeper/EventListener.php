<?php

namespace NgLamVN\minesweeper;

use pocketmine\event\player\PlayerInteractEvent;

use pocketmine\event\Listener;

use NgLamVN\minesweeper\MineSweeper;

class EventListener implements Listener
{
    private $plugin;

    public function __construct(MineSweeper $plugin)
    {
        $this->plugin = $plugin
    }


}
