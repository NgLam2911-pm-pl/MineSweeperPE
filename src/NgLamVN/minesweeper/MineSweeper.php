<?php

namespace NgLamVN\minesweeper;

use pocketmine\plugin\PluginBase;

use NgLamVN\minesweeper\EventListener;
use NgLamVN\minesweeper\GameManager;

use NgLamVN\minesweeper\command\StartMine;
use NgLamVN\minesweeper\command\StopMine;

class MineSweeper extends PluginBase
{

    public $game;

    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvent(new EventListener($this), $this);
        $this->game = new GameManager($this);

        $this->getServer()->getCommandMap()->register("startmine", new StartMine($this));
        $this->getServer()->getCommandMap()->register("stopmine", new StopMine($this));

        $this->getServer()->getLogger()->info("MineSweeperPE TEST VERSION LOADED");
    }
}