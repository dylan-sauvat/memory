<!DOCTYPE html>
<html>
<head>
    <title>Memory Game</title>
    <style>
        .card {
            width: 50px;
            height: 50px;
            background-color: gray;
            float: left;
        }
        .flipped {
            background-image: url(<?php echo $game->getCards()[$i]->getImage(); ?>);
        }
    </style>
</head>
<body>
    <form method="post">
        <?php
        
        // Classe Card pour les cartes du jeu
        class Card {
            private $image;
            private $isFlipped;
            public function __construct($image) {
                $this->image = $image;
                $this->isFlipped = false;
            }
            public function getImage() {
                return $this->image;
            }
            public function flip() {
                $this->isFlipped = !$this->isFlipped;
            }
            public function isFlipped() {
                return $this->isFlipped;
            }
        }

        // Classe Game pour la logique de jeu
        class Game {
            private $cards;
            private $numberOfPairs;
            private $score;
            public function __construct($numberOfPairs) {
                $this->numberOfPairs = $numberOfPairs;
                $this->score = 0;
                $this->cards = array();
                for ($i = 0; $i < $numberOfPairs; $i++) {
                    $this->cards[] = new Card("image" . ($i + 1) . ".jpg");
                    $this->cards[] = new Card("image" . ($i + 1) . ".jpg");
                }
                shuffle($this->cards);
            }
            public function getCards() {
                return $this->cards;
            }
            public function getScore() {
                return $this->score;
            }
            public function flipCard($index) {
                if(!$this->cards[$index]->isFlipped()){
                    $this->cards[$index]->flip();
                }
            }
            public function checkCards() {
                $flippedCards = array();
                for ($i = 0; $i < count($this->cards); $i++) {
                    if ($this->cards[$i]->isFlipped()) {
                        $flippedCards[] = $i;
                    }
                }
                if (count($flippedCards) == 2) {
                    if ($this->cards[$flippedCards[0]]->getImage() == $this->cards[$flippedCards[1]]->getImage()) {
                        $this->score++;
                    } else {
                        $this->cards[$flippedCards[0]]->flip();
                        $this->cards[$flippedCards[1]]->flip();
                    }
                }
            }
        }

        // Instanciation de la classe Game
        $game = new Game(3);

        // Traitement des données envoyées par le formulaire
if (isset($_POST["flip"])) {
    $game->flipCard($_POST["flip"]);
    $game->checkCards();
    }
    
    // Affichage des cartes
    for ($i = 0; $i < count($game->getCards()); $i++) {
    echo '<div class="card ' . ($game->getCards()[$i]->isFlipped() ? 'flipped' : '') . '">';
    echo '<input type="submit" name="flip" value="' . $i . '">';
    echo '</div>';
    }
    
    // Affichage du score
    echo '<p>Score: ' . $game->getScore() . '</p>';
    ?>
    </form>
    <form method="post">
    <label>Nombre de paires de cartes :</label>
    <select name="numberOfPairs">
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
    </select>
    <input type="submit" name="submit" value="Jouer">
    
    <?php
    if (isset($_POST["submit"])) {
    $numberOfPairs = $_POST["numberOfPairs"];
    
	// Validation pour vérifier que la valeur sélectionnée est valide
    if (is_numeric($numberOfPairs) && $numberOfPairs > 1 && $numberOfPairs < 6) {
        $game = new Game($numberOfPairs);
    } else {
       
		// Afficher un message d'erreur si la valeur sélectionnée est invalide
        echo "Veuillez sélectionner un nombre de paires valide.";
    }
}
?>
    </form>
    </body>
    </html>
    