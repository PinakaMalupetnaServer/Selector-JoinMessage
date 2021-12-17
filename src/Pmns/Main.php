<?php

namespace Pmns;

use pocketmine\plugin\PluginBase as P;
use pocketmine\event\Listener as L;
use pocketmine\utils\TextFormat as TF; 

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent as PQ;
use pocketmine\event\player\PlayerInteractEvent as CL;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\nbt\tag\StringTag;
use pocketmine\nbt\tag\ListTag;

use pocketmine\item\Item;
use pocketmine\inventory\Inventory;

class Main extends P implements L {
	
	public function onEnable(){
	    $this->getServer()->getPluginManager()->registerEvents($this, $this);
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool
            switch($cmd->getName()){
		case "games":
		    if($sender instanceof Player){
			$this->OpenUI($sender);
		    }
		break;
	    }
	    return true;
	}
	
	public function onJoin(PlayerJoinEvent $EV){
            $p = $EV->getPlayer();
            $pi = $p->getInventory();
            $pn = $p->getName();
            $name = $pn;
            $p->sendMessage("§l§3-§b-§3-§b-§3-§b-§3-§b-§3-§b-§3-§b- §ePMNS §3-§b-§3-§b-§3-§b-§3-§b-§3-§b-§3-§b-\n§r\n§r     §7Welcome to §l§ePMNS §r§7".$name."\n§r\n     §l§bDISCORD: §r§7https://discord.gg/wt5aH5Bujm\n     §l§dWEBSITE: §r§7SOON\n     §l§aVOTING SITE: §r§7SOON\n§r\n§l§b-§3-§b-§3-§b-§3-§b-§3-§b-§3-§b-§3-");
            $EV->setJoinMessage("§l§7[§a+§7] §r§e".$name);
            $level = $p->getLevel();
            $compass = Item::get(Item::COMPASS);
            $compass->setCustomName("§r§l§eGame Selector");
            $compass->setLore(["§r", 
            "§r§l§c- §r§aRight Click To Open Game Slector UI", 
            "§r"]);
            $p->getInventory()->clearAll();
            $p->getArmorInventory()->clearAll();
            $compass->getNamedTag()->setTat(new StringTag("game", "game"));
            $compass->setNamedTagEntry(new ListTag("ench", []));
            $pi->setItem(4, $compass);
	}
	
	public function onLeave(PQ $e) {
	    $p = $e->getPlayer();
	    $e->setQuitMessage("§l§xc-§7] §r§e".$p->getName());
	}
	
	 public function click(CL $ev){
	     $player = $ev->getPlayer();
	     $item = $player->getInventory()->getItemInHand();
	     if ($item->getNamedTag()->hasTag("game")){
                 $this->OpenUI($player);
	     }
	 }
	 
	public function OpenUI($player){
	    $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
	    $form = $api->createSimpleForm(function (Player $player, int $data = null){
	    $result = $data;
	    if($result === null){
	        return true;
	    }
	    switch($result){
					
	        case 0:
		    $this->getServer()->dispatchCommand($player, "survival");
		break;
				
		case 1:
		    $this->getServer()->dispatchCommand($player, "potpvp");
		break;
				
	        case 2:
	            $player->sendMessage("§l§8[§a!§8] §r§bDUELS §e» §aCOMING SOON!");
		break;
				
		case 3:
				
		break;
	    }
	});
	$form->setTitle("§l§bGamesUI");
	$form->setContent("Please Select Game");
	$form->addButton("§l§2Survival");
	$form->addButton("§l§ePot§bPvP");
	$form->addButton("§l§eDuels\n§7Coming Soon");
	$form->addButton("§l§cEXIT");

	$form->sendToPlayer($player);
	return $form;
	}
}
