<?php

namespace NgLamVN\minesweeper\command;

use pocketmine\command\{Command, CommandSender, PluginCommand};
use pocketmine\{Player, plugin\Plugin, Server};

use NgLamVN\minesweeper\MineSweeper;

class DebugTools extends PluginCommand
{
    private $plugin;

    public function __construct(MineSweeper $plugin)
    {
        parent::__construct("debugmine", $plugin);
        $this->plugin = $plugin;
        $this->setPermission("mspe.command.debug");
        $this->setDescription("MineSweeper Debug Tools");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player)
        {
            $sender->sendMessage("Please use in game !");
            return;
        }
        if (!$this->plugin->game->IsStarted())
        {
            $sender->sendMessage("Please use command while running game !");
        }
        if (isset(!$args[0]))
        {
            $sender->sendMessage("MineSweeper DebugTools");
            $sender->sendMessage("showbomb");
            $sender->sendMessage("showbombflag");
            $sender->sendMessage("bombs");
            $sender->sendMessage("blockremain");
            $sender->sendMessage("showblockid");
            return;
        }
        switch ($args[0])
        {
            case "showbomb":
                $this->plugin->game->ShowBombsLose();
                break;
            case "showbombflag":
                $this->plugin->game->ShowBombsWin();
                break;
            case "bombs":
                $sender->sendMessage("Bombs: " . $this->plugin->game->core->bombs);
                break;
            case "blockremain":
                $sender->sendMessage("Block remain: " . $this->plugin->game->getRemainBlock());
                break;
            case "showblockid"
                $this->plugin->showid($sender);
        }
    }
}
