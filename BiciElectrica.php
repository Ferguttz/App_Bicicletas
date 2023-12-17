<?php
class BiciElectrica {
    private $id; // Identificador de la bicicleta (entero)
    private $coordx; // Coordenada X (entero)
    private $coordy; // Coordenada Y (entero)
    private $bateria; // Carga de la batería en tanto por ciento (entero)
    private $operativa; // Estado de la bicleta ( true operativa- false no disponible)

    public function __set($name, $value) {
       return $this->$name = $value;
    }

    public function __get($name) {
        return $name=='bateria' ? $this->$name."%" : $this->$name;
    }

    public function distancia($x2,$y2) {
        $totalX = $x2 - $this->coordx;
        $totalY = $y2 - $this->coordy;

        $resu = pow($totalX,2) + pow($totalY,2);
        $resu = sqrt($resu);

        return $resu;
    }

    public function __toString()
    {
        return "Identifiacador: ".$this->id." Bateria ".$this->bateria."%";
    }
}