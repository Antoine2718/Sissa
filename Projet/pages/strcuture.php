<?php

class Matrice_IF_3 {
    private $matrice;

    public function __construct() {
        $this->matrice = [
            ['', '', ''],
            ['', '', ''],
            ['', '', ''],
        ];
    }

    public function setValue(int $ligne, int $colonne, string $valeur) {
        if ($this->isValidValue($valeur)) {
            if ($this->isValidPosition($ligne, $colonne)) {
                $this->matrice[$ligne][$colonne] = $valeur;
            } else {
                echo "Position invalide.\n";
            }
        } else {
            echo "Valeur invalide. Utilisez 'X', 'O' ou ''.\n";
        }
    }

    public function getValue(int $ligne, int $colonne): Matrice_IF_3 {
        if ($this->isValidPosition($ligne, $colonne)) {
            return $this->matrice[$ligne][$colonne];
        }
        return null; 
    }

    public function afficherMatrice() {
        foreach ($this->matrice as $ligne) {
            echo implode(" | ", array_map(function($val) {
                return $val === '' ? '' : $val;
            }, $ligne)) . "\n";
        }
    }

    private function isValidValue(string $valeur): string {
        return in_array($valeur, ['X', 'O', '']);
    }

    private function isValidPosition(int $ligne, int $colonne): bool {
        return $ligne >= 0 && $ligne < 3 && $colonne >= 0 && $colonne < 3;
    }
}

$matrice = new Matrice_IF_3();
$matrice->setValue(0, 0, 'X');
$matrice->setValue(1, 1, 'O');
$matrice->setValue(2, 2, '');

$matrice->afficherMatrice();

?>