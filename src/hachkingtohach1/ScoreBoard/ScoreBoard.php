<?php

declare(strict_types=1);

namespace hachkingtohach1\ScoreBoard;

use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use hachkingtohach1\ScoreBoard\task\SendScore;
use hachkingtohach1\SkyWars\SkyWars;

class ScoreBoard extends PluginBase implements Listener {

	private static $instance;

	public function onLoad() :void{
        self::$instance = $this;
	}
	
    public static function getInstance(): ScoreBoard{
        return self::$instance;
    }

	public function onEnable() :void{
		$this->saveDefaultConfig();           		
		$this->getScheduler()->scheduleRepeatingTask(new SendScore(), 20);
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}
	
	public function getPluginName(string $name){
		return $this->getServer()->getPluginManager()->getPlugin($name);
	}
	
	public function getFunction(string $message, Player $player): string{
		$location = $player->getLocation();
		$kills = 0;
		$wins = 0;
		$level = 0;
		$coins = 0;
		$souls = 0;
		$tokens = 0;
		$getAll = SkyWars::getInstance()->getDataBase()->getAll();
		foreach($getAll as $data){
			if($data["xuid"] == $player->getXuid()){
				$kills = (int)$data["kills"];
				$wins = (int)$data["wins"];
				$level = (int)$data["level"];
				$coins = (int)$data["coins"];
				$souls = (int)$data["souls"];
				$tokens = (int)$data["tokens"];
			}
		}
		$nameFunction = [
	        "%kills",
			"%wins",
			"%level",
			"%coins",
			"%souls",
			"%tokens"
	    ];
        $function = [
		    number_format($kills),
			number_format($wins),
			number_format($level),
			number_format($coins),
			number_format($souls),
			number_format($tokens)
		];
		return str_replace($nameFunction, $function, $message);
	}
}