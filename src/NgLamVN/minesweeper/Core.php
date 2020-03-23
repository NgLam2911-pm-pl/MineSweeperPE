<?php

namespace NgLamVN\minesweeper;

use pocketmine\utils\Config;

use NgLamVN\minesweeper\MineSweeper;

class Core
{
    /**
     * MineSweeper Core written in PHP by NgLamVN
     *  Data Values:
     * -2 : Start Position
     * -1 : Opened Position
     *  0 : Non Opened Position
     * 1->8 : Bombs around position
     * 9 : Bomb
     * 10 : Correct Bomb Flag
     * 11 : Wrong Bomb Flag
     */

    public $maxx, $maxy, $bombs;

    public $mine = [];

    public $gameOver;

    public $rbomb;

    private $plugin;

    public function __construct($x, $y, $bombs, MineSweeper $plugin)
    {
        $this->maxx = $x;
        $this->maxy = $y;
        $this->bombs = $bombs;
        $this->gameOver = false;
        $this->rbomb = false;
        $this->GenerateMine();
        $this->plugin = $plugin;
    }

    public function GenerateMine()
    {
        for ($i = 1; $i <= $this->maxx; $i++)
        {
            for ($j = 1; $j <= $this->maxy; $j++)
            {
                $this->mine[$i][$j] = 0;
            }
        }
    }

    public function GenerateBomb ($x, $y)
    {
        $k = 0;
        while ($k < $this->bombs)
        {
            $idx = mt_rand(1, $this->maxx);
            $idy = mt_rand(1, $this->maxy);
            if (($idx <> $x) and ($idx <> ($x+1)) and ($idx <> ($x-1)) and ($idy <> $y) and ($idy <> ($y+1)) and ($idy <> ($y-1)))
            if ($this->mine[$idx][$idy] == 0)
            {
                $this->mine[$idx][$idy] = 9;
                $k++;
            }
        }
        $this->rbomb = true;
    }

    public function explode($x, $y)
    {
        if (!$this->IsExplodeable($x, $y))
        {
            return;
        }
        if ($this->IsHaveFlag($x, $y))
        {
            return;
        }
        if (!$this->IsBombRegistered())
        {
            $this->mine[$x][$y] = -2;
            $this->GenerateBomb($x, $y);
            $this->checkAround($x, $y);
            return;
        }
        if ($this->mine[$x][$y] == 9)
        {
            $this->gameOver = true;
            return;
        }
        if (!($this->mine[$x][$y] == 0))
        {
            return;
        }
        $this->mine[$x][$y] = -1;
        $this->checkAround($x, $y);
    }

    public function checkBombsAround ($x, $y)
    {
        $abomb = 0;
        if ($this->IsExplodeable($x-1, $y+1))
        {
            if ($this->mine[$x-1][$y+1] == 9)
            {
                $abomb++;
            }
        }
        if ($this->IsExplodeable($x, $y+1))
        {
            if ($this->mine[$x][$y+1] == 9)
            {
                $abomb++;
            }
        }
        if ($this->IsExplodeable($x+1, $y+1))
        {
            if ($this->mine[$x+1][$y+1] == 9)
            {
                $abomb++;
            }
        }
        if ($this->IsExplodeable($x+1, $y))
        {
            if ($this->mine[$x+1][$y] == 9)
            {
                $abomb++;
            }
        }
        if ($this->IsExplodeable($x+1, $y-1))
        {
            if ($this->mine[$x+1][$y-1] == 9)
            {
                $abomb++;
            }
        }
        if ($this->IsExplodeable($x, $y-1))
        {
            if ($this->mine[$x][$y-1] == 9)
            {
                $abomb++;
            }
        }
        if ($this->IsExplodeable($x-1, $y-1))
        {
            if ($this->mine[$x-1][$y-1] == 9)
            {
                $abomb++;
            }
        }
        if ($this->IsExplodeable($x-1, $y))
        {
            if ($this->mine[$x-1][$y] == 9)
            {
                $abomb++;
            }
        }
        return $abomb;
    }

    public function checkAround ($x, $y)
    {
        $bombs = $this->checkBombsAround($x, $y);
        if ($bombs == 0)
        {
            $this->mine[$x][$y] = -1;
            if ($this->IsExplodeable($x - 1, $y + 1))
            {
                if ($this->mine[$x-1][$y+1] == 0)
                {
                    $this->checkAround($x - 1, $y + 1);
                }
            }
            if ($this->IsExplodeable($x, $y + 1))
            {
                if ($this->mine[$x][$y+1] == 0)
                {
                    $this->checkAround($x, $y + 1);
                }
            }
            if ($this->IsExplodeable($x + 1, $y + 1))
            {
                if ($this->mine[$x+1][$y+1] == 0)
                {
                    $this->checkAround($x+1, $y+1);
                }
            }
            if ($this->IsExplodeable($x+1, $y))
            {
                if ($this->mine[$x+1][$y] == 0)
                {
                    $this->checkAround($x+1, $y);
                }

            }
            if ($this->IsExplodeable($x+1, $y-1))
            {
                if ($this->mine[$x+1][$y-1] == 0)
                {
                    $this->checkAround($x+1, $y-1);
                }
            }
            if ($this->IsExplodeable($x, $y-1))
            {
                if ($this->mine[$x][$y-1] == 0)
                {
                    $this->checkAround($x, $y-1);
                }
            }
            if ($this->IsExplodeable($x-1, $y-1))
            {
                if ($this->mine[$x-1][$y-1] == 0)
                {
                    $this->checkAround($x-1, $y-1);
                }
            }
            if ($this->IsExplodeable($x-1, $y))
            {
                if ($this->mine[$x-1][$y] == 0)
                {
                    $this->checkAround($x-1, $y);
                }
            }
        }
        else
        {
            $this->mine[$x][$y] = $bombs;
        }
    }

    public function IsExplodeable($x, $y)
    {
        if (isset($this->mine[$x][$y]))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function IsGameOver()
    {
        return $this->gameOver;
    }

    public function IsBombRegistered()
    {
        return $this->rbomb;
    }

    public function IsHaveFlag ($x, $y)
    {
        if (($this->mine[$x][$y] == 10) or ($this->mine[$x][$y] == 11))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function setBombFlag ($x, $y)
    {
        if (!$this->IsExplodeable($x, $y))
        {
            return;
        }
        if ($this->mine[$x][$y] == 0)
        {
            $this->mine[$x][$y] = 11;
        }
        if ($this->mine[$x][$y] == 9)
        {
            $this->mine[$x][$y] = 10;
        }
        if ($this->mine[$x][$y] == 11)
        {
            $this->mine[$x][$y] = 0;
        }
        if ($this->mine[$x][$y] == 10)
        {
            $this->mine[$x][$y] = 9;
        }

    }
}
