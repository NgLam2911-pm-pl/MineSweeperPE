<?php

namespace NgLamVN\minesweeper;

use pocketmine\plugin\PluginBase;

use NgLamVN\minesweeper\EventListener;
use NgLamVN\minesweeper\GameManager;

class MineSweeper extends PluginBase
{

    public $game;

    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvent(new EventListener($this), $this);
        $this->game = new GameManager($this);

        $this->getServer()->getLogger()->info("MineSweeperPE TEST VERSION LOADED");
    }
}