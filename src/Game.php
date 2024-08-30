<?php

// Modèle : Représente un joueur et ses données
class Player
{
    public $name;
    public $health;
    public $attack;
    public $defense;

    public function __construct($name, $health, $attack, $defense)
    {
        $this->name = $name;
        $this->health = (int)$health;
        $this->attack = (int)$attack;
        $this->defense = (int)$defense;
    }

    public function isAlive()
    {
        return $this->health > 0;
    }

    public function takeDamage($damage)
    {
        $this->health -= max(0, $damage);
    }
}

// Modèle : Gère les combats entre joueurs
class Battle
{
    public function attackPlayer(Player $attacker, Player $defender)
    {
        $damage = $attacker->attack - $defender->defense;
        $defender->takeDamage($damage);
    }
}

// Vue : Gère l'affichage
class GameView
{
    public function displayHealth(Player $player1, Player $player2)
    {
        echo "{$player1->health} {$player2->health}\n";
    }

    public function displayWinner(Player $winner)
    {
        echo "{$winner->name} {$winner->health}\n";
    }

    public function displayDraw()
    {
        echo "Draw\n";
    }
}

// Contrôleur : Fonctionnement du jeu
class Game
{
    protected $player1;
    protected $player2;
    protected $battle;
    protected $view;

    /**
     * @param $input 2 lignes, comprenant les infos de chaque joueur
     * exemple: "Bod 100 7 5\nAlice 80 9 3"
     */
    public function __construct($input)
    {
        $lines = explode("\n", $input);
        $player1Data = explode(" ", $lines[0]);
        $player2Data = explode(" ", $lines[1]);

        $this->player1 = new Player($player1Data[0], $player1Data[1], $player1Data[2], $player1Data[3]);
        $this->player2 = new Player($player2Data[0], $player2Data[1], $player2Data[2], $player2Data[3]);

        $this->battle = new Battle();
        $this->view = new GameView();
    }

    public function run()
    {
        // Boucle de jeu infligeant des dégats tant que les deux joueurs ont des PV
        while ($this->player1->isAlive() && $this->player2->isAlive()) {
            $this->battle->attackPlayer($this->player1, $this->player2);
            if (!$this->player2->isAlive()) {
                break;
            }

            $this->battle->attackPlayer($this->player2, $this->player1);

            // Affichage des PV après chaque tour
            $this->view->displayHealth($this->player1, $this->player2);
        }

        // Affichage du résultat final
        if (!$this->player1->isAlive() && !$this->player2->isAlive()) {
            $this->view->displayDraw();
        } elseif (!$this->player1->isAlive()) {
            $this->view->displayWinner($this->player2);
        } else {
            $this->view->displayWinner($this->player1);
        }
    }
}