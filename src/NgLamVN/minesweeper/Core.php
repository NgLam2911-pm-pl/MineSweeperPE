<?php

namespace NgLamVN\minesweeper;

use pocketmine\utils\Config;

use NgLamVN\minesweeper\MineSweeper;

class Core
{
    /**
     * MineSweeper Core written in PHP by NgLamVN
     *  Data Values...
     */

    public const START_POSITION = -2;
    public const OPENED_POSITION = -1;
    public const NON_OPENED_POSITION = 0;
    public const BOMB_AROUND_POSITION = [null, 1, 2, 3, 4, 5, 6, 7, 8];
    public const BOMB = 9;
    public const CORRECT_BOMB_FLAG = 10;
    public const WRONG_BOMB_FLAG = 11;
    public const CORRECT_QUESTION_FLAG = 12;
    public const WRONG_QUESTION_FLAG = 13;

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
            $this->mine[$x][$y] = -2;   //TO DO: Make safe area
            $this->mine[$x-1][$y+1] = -1;
            $this->mine[$x][$y+1] = -1;
            $this->mine[$x+1][$y+1] = -1;
            $this->mine[$x+1][$y] = -1;
            $this->mine[$x+1][$y-1] = -1;
            $this->mine[$x][$y-1] = -1;
            $this->mine[$x-1][$y-1] = -1;
            $this->mine[$x-1][$y] = -1;

            $this->GenerateBomb($x, $y);

            $this->mine[$x-1][$y+1] = 0;
            $this->mine[$x][$y+1] = 0;
            $this->mine[$x+1][$y+1] = 0;
            $this->mine[$x+1][$y] = 0;
            $this->mine[$x+1][$y-1] = 0;
            $this->mine[$x][$y-1] = 0;
            $this->mine[$x-1][$y-1] = 0;
            $this->mine[$x-1][$y] = 0;

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
            if (($this->mine[$x-1][$y+1] == 9) or ($this->mine[$x-1][$y+1] == 10))
            {
                $abomb++;
            }
        }
        if ($this->IsExplodeable($x, $y+1))
        {
            if (($this->mine[$x][$y+1] == 9) or ($this->mine[$x][$y+1] == 10))
            {
                $abomb++;
            }
        }
        if ($this->IsExplodeable($x+1, $y+1))
        {
            if (($this->mine[$x+1][$y+1] == 9) or ($this->mine[$x+1][$y+1] == 10))
            {
                $abomb++;
            }
        }
        if ($this->IsExplodeable($x+1, $y))
        {
            if (($this->mine[$x+1][$y] == 9) or ($this->mine[$x+1][$y] == 10))
            {
                $abomb++;
            }
        }
        if ($this->IsExplodeable($x+1, $y-1))
        {
            if (($this->mine[$x+1][$y-1] == 9) or ($this->mine[$x+1][$y-1] == 10))
            {
                $abomb++;
            }
        }
        if ($this->IsExplodeable($x, $y-1))
        {
            if (($this->mine[$x][$y-1] == 9) or ($this->mine[$x][$y-1] == 10))
            {
                $abomb++;
            }
        }
        if ($this->IsExplodeable($x-1, $y-1))
        {
            if (($this->mine[$x-1][$y-1] == 9) or ($this->mine[$x-1][$y-1] == 10))
            {
                $abomb++;
            }
        }
        if ($this->IsExplodeable($x-1, $y))
        {
            if (($this->mine[$x-1][$y] == 9) or ($this->mine[$x-1][$y] == 10))
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
            return;
        }
        if ($this->mine[$x][$y] == 9)
        {
            $this->mine[$x][$y] = 10;
            return;
        }
        if ($this->mine[$x][$y] == 11)
        {
            $this->mine[$x][$y] = 0;
            return;
        }
        if ($this->mine[$x][$y] == 10)
        {
            $this->mine[$x][$y] = 9;
            return;
        }

    }
}
