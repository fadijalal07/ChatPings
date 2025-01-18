<?php

namespace Fadi\ChatPings;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener
{
    private $config;

    public function onEnable(): void
    {
        $this->saveDefaultConfig();
        $this->config = $this->getConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onChat(PlayerChatEvent $event)
    {
        $message = $event->getMessage();
        $server = $this->getServer();

        foreach ($server->getOnlinePlayers() as $onlinePlayer) {
            if (strpos($message, "@" . $onlinePlayer->getName()) !== false) {
                $this->playSound($onlinePlayer, $this->config->get("sound"), $this->config->get("volume"), $this->config->get("pitch"));
            }
        }
    }

    /**
     * @param string $sound
     */
    public function playSound(Player $player, string $sound, $volume = 1, $pitch = 1): void
    {
        $spk = new PlaySoundPacket();
        $spk->soundName = $sound;
        $spk->x = $player->getLocation()->getX();
        $spk->y = $player->getLocation()->getY();
        $spk->z = $player->getLocation()->getZ();
        $spk->volume = $volume;
        $spk->pitch = $pitch;
        $player->getNetworkSession()->sendDataPacket($spk);
    }
}
