<?php
class Utilisateur {
        private $identifiant;
        private $points;
        private $idRang;
        private $type;
        private $id;
        public function __construct($id,$identifiant, $points, $idRang, $type) {
            $this->identifiant = $identifiant;
            $this->points = $points;
            $this->idRang = $idRang;
            $this->type = $type;
            $this->id=$id;
        }
        public function getID() {
            return $this->id;
        }

        public function setID($id) {
            $this->id = $id;
        }
        
        public function getIdentifiant() {
            return $this->identifiant;
        }

        public function setIdentifiant($identifiant) {
            $this->identifiant = $identifiant;
        }

        public function getPoints() {
            return $this->points;
        }

        public function setPoints($points) {
            $this->points = $points;
        }

        public function getIdRang() {
            return $this->idRang;
        }

        public function setIdRang($idRang) {
            $this->idRang = $idRang;
        }

        public function getType() {
            return $this->type;
        }

        public function setType($type) {
            $this->type = $type;
        }
}
?>