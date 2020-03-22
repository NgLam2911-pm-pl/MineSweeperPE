<?php

namespace NgLamVN\minesweeper;

use pocketmine\block\Block;
use pocketmine\level\Level;
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
        $this->plugin =$plugin;
        $this->start = false;
    }

    public function startGame($x, $y, $bombs)
    {
        $this->core = new Core($x, $y, $bombs);
        $this->start = true;
    }

    public function closeGame()
    {
        $level = $this->plugin->getServer()->getLevelByName("Game");
        $x = $this->core->maxx;
        $y = $this->core->maxy;
        for ($i = 1; $i <= $x; $i++)
        {
            for ($j = 1; $j <= $y; $j++)
            {
                $pos = new Vector3($i, 10, $j);
                $level->setBlock($pos, Block::AIR, false, false);
            }
        }
        $this->start = false;
    }
    public function reloadMine()
    {
        $level = $this->plugin->getServer()->getLevelByName("Game");
        $x = $this->core->maxx;
        $y = $this->core->maxy;
        for ($i = 1; $i <= $x; $i++)
        {
            for ($j = 1; $j <= $y; $j ++)
            {
                $pos = new Vector3($i, 10, $j);
                switch($this->core->mine[$i][$j])
                {
                    case -2:
                        $level->setBlock($pos, Block::AIR, false, false);
                        break;
                    case -1:
                        $level->setBlock($pos, Block::AIR, false, false);
                        break;
                    case 0:
                        $level->setBlock($pos, Block::QUARTZ_BLOCK, false, false);
                        break;
                    case 1:
                        $level->setBlock($pos, Block::STONE, false, false);
                        break;
                    case 2:
                        $level->setBlock($pos, Block::GRASS, false, false);
                        break;
                    case 3:
                        $level->setBlock($pos, Block::DIRT, false, false);
                        break;
                    case 4:
                        $level->setBlock($pos, Block::COBBLESTONE, false, false);
                        break;
                    case 5:
                        $level->setBlock($pos, Block::PLANKS, false, false);
                        break;
                    case 6:
                        $level->setBlock($pos, Block::WOOL, false, false);
                        break;
                    case 7:
                        $level->setBlock($pos, Block::GLASS, false, false);
                        break;
                    case 8:
                        $level->setBlock($pos, Block::OBSIDIAN, false, false);
                        break;
                    case 9:
                        $level->setBlock($pos, Block::QUARTZ_BLOCK, false, false);
                        break;
                    case 10:
                        $level->setBlock($pos, Block::GOLD_BLOCK, false, false);
                        break;
                    case 11:
                        $level->setBlock($pos, Block::INFO_UPDATE, false, false);
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
        for ($i = 1; $i <= $x; $i++)
        {
            for ($j = 1; $j <= $y; $j ++)
            {
                $pos = new Vector3($i, 10, $j);
                if ($this->core->mine[$i][$j] = 9)
                {
                    $level->setBlock($pos, Block::TNT, false, false);
                }
            }
        }
    }

    public function ShowBombsWin()
    {
        $level = $this->plugin->getServer()->getLevelByName("Game");
        $x = $this->core->maxx;
        $y = $this->core->maxy;
        for ($i = 1; $i <= $x; $i++)
        {
            for ($j = 1; $j <= $y; $j ++)
            {
                $pos = new Vector3($i, 10, $j);
                if ($this->core->mine[$i][$j] = 9)
                {
                    $level->setBlock($pos, Block::GOLD_BLOCK, false, false);
                }
            }
        }
    }

    public function explode($x,$y)
    {
        $this->core->explode($x,$y);
        if ($this->core->IsGameOver())
        {
            $this->ShowBombsLose();
            $this->plugin->getServer()->broadcastMessage("GAME OVERRRR, /startmine to start new game");
            $this->start = false;
        }
        else
        {
            $this->reloadMine();
        }
        if ($this->getRemainBlock() = $this->core->bombs)
        {
            $this->ShowBombsWin();
            $this->plugin->getServer()->broadcastMessage("YOU WINNNNN /startmine to start new game");
            $this->start = false;
        }
    }

    public function getRemainBlock()
    {
        $blocks = 0;
        for ($i = 1; $i <= $x; $i++)
        {
            for ($j = 1; $j <= $y; $j++)
            {
                if (($this->core->mine[$i][$j] = 0) or ($this->core->mine[$i][$j] = 10) or ($this->core->mine[$i][$j] = 11))
                {
                    $blocks++
                }
            }
        }
        return $blocks;
    }
}
