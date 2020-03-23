<?php

namespace NgLamVN\minesweeper;

use pocketmine\block\Block;
use pocketmine\math\Vector3;
use pocketmine\{Server, Player};

use NgLamVN\minesweeper\MineSweeper;
use NgLamVN\minesweeper\Core;

class GameManager
{
    private $plugin;

    public $core;

    public $start;

    public function getPlugin()
    {
        return $this->plugin;
    }

    public function __construct(MineSweeper $plugin)
    {
        $this->plugin = $plugin;
        $this->start = false;
    }

    public function startGame($x, $y, $bombs)
    {
        $this->core = new Core($x, $y, $bombs, $this->plugin);
        $this->start = true;
        $this->reloadMine();
    }
    public function closeGame()
    {
        $level = $this->plugin->getServer()->getLevelByName("Game");
        $x = $this->core->maxx;
        $y = $this->core->maxy;
        for ($i = 1; $i <= $x; $i++) {
            for ($j = 1; $j <= $y; $j++) {
                $pos = new Vector3($i, 10, $j);
                $level->setBlock($pos, Block::get(Block::AIR), false, false);
            }
        }
        $this->start = false;
    }

    public function reloadMine()
    {
        $level = $this->plugin->getServer()->getLevelByName("Game");
        $x = $this->core->maxx;
        $y = $this->core->maxy;
        for ($i = 1; $i <= $x; $i++) {
            for ($j = 1; $j <= $y; $j++) {
                $pos = new Vector3($i, 10, $j);
                switch ($this->core->mine[$i][$j])
                {
                    case -1:
                    case -2:
                        $level->setBlock($pos, Block::get(Block::AIR), false, true);
                        break;
                    case 0:
                        $level->setBlock($pos, Block::get(Block::QUARTZ_BLOCK), false, true);
                        break;
                    case 1:
                        $level->setBlock($pos, Block::get(Block::STONE), false, true);
                        break;
                    case 2:
                        $level->setBlock($pos, Block::get(Block::GRASS), false, true);
                        break;
                    case 3:
                        $level->setBlock($pos, Block::get(Block::DIRT), false, true);
                        break;
                    case 4:
                        $level->setBlock($pos, Block::get(Block::COBBLESTONE), false, true);
                        break;
                    case 5:
                        $level->setBlock($pos, Block::get(Block::PLANKS), false, true);
                        break;
                    case 6:
                        $level->setBlock($pos, Block::get(Block::WOOL), false, true);
                        break;
                    case 7:
                        $level->setBlock($pos, Block::get(Block::GLASS), false, true);
                        break;
                    case 8:
                        $level->setBlock($pos, Block::get(Block::OBSIDIAN), false, true);
                        break;
                    case 10:
                        $level->setBlock($pos, Block::get(Block::GOLD_BLOCK), false, true);
                        break;
                    case 11:
                        $level->setBlock($pos, Block::get(Block::GOLD_BLOCK), false, true);
                        break;
                    case 9:
                        $level->setBlock($pos, Block::get(Block::QUARTZ_BLOCK), false, true);
                        break;
                }
            }
        }
    }

    public function ShowBombsLose()
    {
        $level = $this->plugin->getServer()->getLevelByName("Game");
        $x = $this->core->maxx;
        $y = $this->core->maxy;
        for ($i = 1; $i <= $x; $i++) {
            for ($j = 1; $j <= $y; $j++) {
                $pos = new Vector3($i, 10, $j);
                if ($this->core->mine[$i][$j] == 9) {
                    $level->setBlock($pos, Block::get(Block::TNT), false, true);
                }
            }
        }
    }

    public function ShowBombsWin()
    {
        $level = $this->plugin->getServer()->getLevelByName("Game");
        $x = $this->core->maxx;
        $y = $this->core->maxy;
        for ($i = 1; $i <= $x; $i++) {
            for ($j = 1; $j <= $y; $j++) {
                $pos = new Vector3($i, 10, $j);
                if ($this->core->mine[$i][$j] == 9) {
                    $level->setBlock($pos, Block::get(Block::GOLD_BLOCK), false, true);
                }
            }
        }
    }

    public function explode($x, $y)
    {
        $this->core->explode($x, $y);
        if ($this->core->IsGameOver())
        {
            $this->ShowBombsLose();
            $this->plugin->getServer()->broadcastMessage("GAME OVERRRR, /startmine to start new game");
            $this->start = false;
            return;
        }
        $blocks = $this->getRemainBlock();
        $this->plugin->getServer()->getLogger()->info("Block Remain: " .$blocks);
        $bombs = $this->core->bombs;
        $this->plugin->getServer()->getLogger()->info("Bombs: " .$bombs);
        if ($blocks == 0)
        {
            $this->ShowBombsWin();
            $this->plugin->getServer()->broadcastMessage("YOU WINNNNN /startmine to start new game");
            $this->start = false;
            return;
        }
        $this->reloadMine();
    }

    public function getRemainBlock()
    {
        $blocks = 0;
        $x = $this->core->maxx;
        $y = $this->core->maxy;
        for ($i = 1; $i <= $x; $i++)
        {
            for ($j = 1; $j <= $y; $j++)
            {
                if (($this->core->mine[$i][$j] == 0) or ($this->core->mine[$i][$j] == 10) or ($this->core->mine[$i][$j] == 11))
                {
                    $blocks++;
                }
            }
        }
        return $blocks;
    }

    public function IsStarted()
    {
        return $this->start;
    }

    public function IsInMine($x, $y)
    {
        return $this->core->IsExplodeable($x, $y);
    }
}