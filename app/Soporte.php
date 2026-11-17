<?php
namespace Dwes\ProyectoVideoclub;
abstract class Soporte implements Resumible{
    public String $titulo;
    public bool $alquilado=false;
    protected int $numero;
    private float $precio;

    private static int $IVA=21;

    /**
     * @param int $numero
     * @param float $precio
     * @param String $titulo
     */
    public function __construct(string $titulo, int $numero, float $precio )
    {

        $this->titulo = $titulo;
        $this->numero = $numero;
        $this->precio = $precio;
    }

    public function getPrecio(): float
    {
        return $this->precio;
    }

    /**
     * @return int
     */
    public function getNumero(): int
    {
        return $this->numero;
    }

    public function getPrecioConIva(): float
    {
        return round($this->precio*(1+ self::$IVA / 100), 2);
    }

    public function muestraResumen():void
    {
        echo $this->titulo;
        echo "<br>". $this->precio;
        echo "<br>". $this->getPrecioConIva();
    }

}
