<?php

namespace NgLamVN\minesweeper;

use pocketmine\utils\Config;

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
     * 10 : Bomb Flag
     * 11 : Question Flag
     */

    public $maxx, $maxy, $bombs;

    public $mine = [];

    public $gameOver;

    public function __construct($x, $y, $bombs)
    {
        $this->maxx = $x;
        $this->maxy = $y;
        $this->bombs = $bombs;
        $this->gameOver = false;
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

    public function GenerateBomb ()
    {
        $k = 0;
        while ($k <= $this->bombs)
        {
            $idx = mt_rand(1, $this->maxx);
            $idy = mt_rand(1, $this->maxy);
            if ($this->mine[$idx][$idy] == 0)
            {
                $this->mine[$idx][$idy] = 9;
                $k++;
            }
        }
    }

    public function explode($x, $y)
    {
        if (!$this->IsExplodeable($x, $y))
        {
            return;
        }
        if ($this->mine[$x][$y] = 9)
        {
            $this->gameOver = true;
            return;
        }
        if (!($this->mine[$x][$y] = 0))
        {
            return;
        }
        $this->mine[$x][$y] = -1;
    }

    public function checkBombsAround ($x, $y)
    {
        $abom = 0;
        if ($this->IsExplodeable($x-1, $y+1))
        {
            if ($this->mine[$x-1][$y+1] = 9)
            {
                $abomb++;
            }
        }
        if ($this->IsExplodeable($x, $y+1))
        {
            if ($this->mine[$x][$y+1] = 9)
            {
                $abomb++;
            }
        }
        if ($this->IsExplodeable($x+1, $y+1))
        {
            if ($this->mine[$x+1][$y+1] = 9)
            {
                $abomb++;
            }
        }
        if ($this->IsExplodeable($x+1, $y))
        {
            if ($this->mine[$x+1][$y] = 9)
            {
                $abomb++;
            }
        }
        if ($this->IsExplodeable($x+1, $y-1))
        {
            if ($this->mine[$x+1][$y-1] = 9)
            {
                $abomb++;
            }
        }
        if ($this->IsExplodeable($x, $y-1))
        {
            if ($this->mine[$x][$y-1] = 9)
            {
                $abomb++;
            }
        }
        if ($this->IsExplodeable($x-1, $y-1))
        {
            if ($this->mine[$x-1][$y-1] = 9)
            {
                $abomb++;
            }
        }
        if ($this->IsExplodeable($x-1, $y))
        {
            if ($this->mine[$x-1][$y] = 9)
            {
                $abomb++;
            }
        }
        return $abomb;
    }

    public function checkAround ($x, $y)
    {
        $bombs = $this->checkBombsAround($x, $y);
        if ($bombs = 0) {
            $this->mine[$x][$y] = -1;
            if ($this->IsExplodeable($x - 1, $y + 1)) {
                $this->checkAround($x - 1, $y + 1);
            }
            if ($this->IsExplodeable($x, $y + 1)) {
                $this->checkAround($x, $y + 1);
            }
            if ($this->IsExplodeable($x + 1, $y + 1))
            {
                $this->checkAround($x+1, $y+1);
            }
            if ($this->IsExplodeable($x+1, $y))
            {
                $this->checkAround($x+1, $y);
            }
            if ($this->IsExplodeable($x+1, $y-1))
            {
                $this->checkAround($x+1, $y-1);
            }
            if ($this->IsExplodeable($x, $y-1))
            {
                $this->checkAround($x, $y-1);
            }
            if ($this->IsExplodeable($x-1, $y-1))
            {
                $this->checkAround($x-1, $y-1);
            }
            if ($this->IsExplodeable($x-1, $y))
            {
                $this->checkAround($x-1, $y);
            }
        }
        else
        {
            $this->mine[$x][$y] = $boms;
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

}
