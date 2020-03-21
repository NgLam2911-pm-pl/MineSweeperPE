<?php

namespace NgLamVN\minesweeper;

class Core
{
    /**
     * MineSweeper Core written in PHP by NgLamVN
     *  Data Values:
     * -2 : Start Position
     * -1 : Opened Position
     *  0 : Non Opened Position
     * 1->8 : boms around position
     * 9 : Bom
     * 10 : Bom Flag
     * 11 : Question Flag
     */

    public $maxx, $maxy, $boms;

    public $mine = [];

    public $gameOver = false;

    public function __construct($x, $y, $boms)
    {
        $this->maxx = $x;
        $this->maxy = $y;
        $this->boms = $boms;
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

    public function GenerateBom ()
    {
        $k = 0;
        while ($k <= $this->boms)
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

    public function checkBomsAround ($x, $y)
    {
        $abom = 0;
        if ($this->IsExplodeable($x-1, $y+1))
        {
            if ($this->mine[$x-1][$y+1] = 9)
            {
                $abom++;
            }
        }
        if ($this->IsExplodeable($x, $y+1))
        {
            if ($this->mine[$x][$y+1] = 9)
            {
                $abom++;
            }
        }
        if ($this->IsExplodeable($x+1, $y+1))
        {
            if ($this->mine[$x+1][$y+1] = 9)
            {
                $abom++;
            }
        }
        if ($this->IsExplodeable($x+1, $y))
        {
            if ($this->mine[$x+1][$y] = 9)
            {
                $abom++;
            }
        }
        if ($this->IsExplodeable($x+1, $y-1))
        {
            if ($this->mine[$x+1][$y-1] = 9)
            {
                $abom++;
            }
        }
        if ($this->IsExplodeable($x, $y-1))
        {
            if ($this->mine[$x][$y-1] = 9)
            {
                $abom++;
            }
        }
        if ($this->IsExplodeable($x-1, $y-1))
        {
            if ($this->mine[$x-1][$y-1] = 9)
            {
                $abom++;
            }
        }
        if ($this->IsExplodeable($x-1, $y))
        {
            if ($this->mine[$x-1][$y] = 9)
            {
                $abom++;
            }
        }
        return $abom;
    }

    public function checkAround ($x, $y)
    {
        $boms = $this->checkBomsAround($x, $y);
        if ($boms = 0) {
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