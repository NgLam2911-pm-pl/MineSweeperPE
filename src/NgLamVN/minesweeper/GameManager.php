<?php

declare(strict_types = 1);

namespace NgLamVN\minesweeper;

use pocketmine\block\Block;
use pocketmine\math\Vector3;
use pocketmine\{item\Item, Server, Player};

use NgLamVN\minesweeper\MineSweeper;
use NgLamVN\minesweeper\Core;
use NgLamVN\minesweeper\task\StatusTask;

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
        $this->closeGame();
        $this->core = new Core($x, $y, $bombs, $this->plugin);
        $this->start = true;
        $this->reloadMine();
        $task = new StatusTask($this->plugin);
        $this->plugin->getScheduler()->scheduleRepeatingTask($task, 40);
        $this->giveItem();
    }
    public function closeGame()
    {
        $level = $this->plugin->getServer()->getLevelByName("Game");
        if (!isset($this->core->maxx))
        {
            return;
        }
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

    public function ShowBombsLose($tapx = 0, $tapy = 0)
    {
        $level = $this->plugin->getServer()->getLevelByName("Game");
        $x = $this->core->maxx;
        $y = $this->core->maxy;
        for ($i = 1; $i <= $x; $i++)
        {
            for ($j = 1; $j <= $y; $j++)
            {
                $pos = new Vector3($i, 10, $j);
                if ($this->core->mine[$i][$j] == 9)
                {
                    $level->setBlock($pos, Block::get(Block::TNT), false, true);
                }
                if ($this->core->mine[$i][$j] == 11)
                {
                    $level->setBlock($pos, Block::get(Block::REDSTONE_BLOCK), false, true);
                }
            }
        }
        if (($tapx <> 0) and ($tapy <> 0))
        {
            $pos2 = new Vector3($tapx, 10, $tapy);
            $level->setBlock($pos2, Block::get(Block::BEDROCK), false, true);
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
        $this->reloadMine();
        if ($this->core->IsGameOver())
        {
            $this->ShowBombsLose($x, $y);
            $this->plugin->getServer()->getLogger()->info("§bShow lose:§a " . $x . " " . $y);
            $this->plugin->getServer()->broadcastMessage("§f[§bMineSweeper§f]§c GAME OVER, use§e /startmine§c to start another MineSweeper game.");
            $this->start = false;
            $this->clearItem();
            return;
        }
        $blocks = $this->getRemainBlock();
        $this->plugin->getServer()->getLogger()->info("§aBlocks Remain:§e " .$blocks);
        $bombs = $this->core->bombs;
        $this->plugin->getServer()->getLogger()->info("§aMine:§e " .$bombs);
        if ($blocks == 0)
        {
            $this->ShowBombsWin();
            $this->plugin->getServer()->broadcastMessage("§f[§bMineSweeper§f]§a Congratstulation! You've won the game, use§e /startmine§a to start another MineSweeper game.");
            $this->start = false;
            return;
        }
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
                if (($this->core->mine[$i][$j] == 0) or ($this->core->mine[$i][$j] == 11))
                {
                    $blocks++;
                }
            }
        }
        return $blocks;
    }

    public function getBombLeft()
    {
        $dbomb = 0;
        $rbomb = $this->core->bombs;
        $x = $this->core->maxx;
        $y = $this->core->maxy;
        for ($i = 1; $i <= $x; $i++)
        {
            for ($j = 1; $j <= $y; $j++)
            {
                if (($this->core->mine[$i][$j] == 10) or ($this->core->mine[$i][$j] == 11))
                {
                    $dbomb++;
                }
            }
        }
        return ($rbomb - $dbomb);
    }

    public function IsStarted()
    {
        return $this->start;
    }

    public function IsInMine($x, $y)
    {
        return $this->core->IsExplodeable($x, $y);
    }

    public function giveItem()
    {
        $players = $this->plugin->getServer()->getLevelByName("Game")->getPlayers();
        foreach ($players as $player)
        {
            $player->getInventory()->clearAll();
            $item1 = Item::get(Item::IRON_SHOVEL);
            $item2 = Item::get(Item::BLAZE_ROD);
            $item1->setCustomName("§c§lExplode§r\n§eTap to Explode");
            $item2->setCustomName("§e§lFlag§r\n§eTap to set flag");
            $player->getInventory()->addItem($item1);
            $player->getInventory()->addItem($item2);
        }
    }
    public function clearItem()
    {
        $players = $this->plugin->getServer()->getLevelByName("Game")->getPlayers();
        foreach ($players as $player)
        {
            $player->getInventory()->clearAll();
        }
    }
}
