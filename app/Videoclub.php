<?php
namespace Dwes\ProyectoVideoclub;
class Videoclub {
    private $nombre;
    private $productos=[];
    private $numProductos=0;
    private $socios=[];
    private $numSocios=0;

    /**
     * @param $nombre
     * @param array $productos
     * @param $numProductos
     * @param array $socios
     * @param $numSocios
     */
    public function __construct($nombre)
    {
        $this->nombre = $nombre;

    }

    private function incluirProducto(Soporte $producto){
        $this->productos[] = $producto;
        $this->numProductos++;
        echo"<br>Producto introducido correctamente";
    }


    public function incluirCintaVideo($titulo, $precio, $duracion) {
        $cinta = new CintaVideo($titulo, $this->numProductos, $precio, $duracion);
        $this->incluirProducto($cinta);
    }

    public function incluirDvd($titulo, $precio, $idiomas, $pantalla) {
        $dvd = new Dvd($titulo, $this->numProductos, $precio, $idiomas, $pantalla);
        $this->incluirProducto($dvd);
    }

    public function incluirJuego($titulo, $precio, $consola, $minJ, $maxJ) {
        $juego = new Juego($titulo, $this->numProductos, $precio, $consola, $minJ, $maxJ);
        $this->incluirProducto($juego);
    }

    public function incluirSocio($nombre, $maxAlquileresConcurrentes = 3) {
        $socio = new Cliente($nombre, $this->numSocios, $maxAlquileresConcurrentes);
        $this->socios[] = $socio;
        $this->numSocios++;
        echo "<br>Socio incluido correctamente.";
    }

    public function listarProductos() {
        echo "<br><strong>Listado de Productos del Videoclub</strong>";
        foreach ($this->productos as $producto) {
            $producto->muestraResumen();
        }
    }

    public function listarSocios() {
        echo "<br><strong>Listado de Socios del Videoclub</strong>";
        foreach ($this->socios as $socio) {
            $socio->muestraResumen();
        }
    }

    public function alquilaSocioProducto($numeroCliente, $numeroSoporte):self {
        $clienteEncontrado = null;
        $soporteEncontrado = null;

        foreach ($this->socios as $socio) {
            if ($socio->getNumero() === $numeroCliente) {
                $clienteEncontrado = $socio;
                break;
            }
        }

        foreach ($this->productos as $producto) {
            if ($producto->getNumero() === $numeroSoporte) {
                $soporteEncontrado = $producto;
                break;
            }
        }

        if ($clienteEncontrado !== null && $soporteEncontrado !== null) {
            $clienteEncontrado->alquilar($soporteEncontrado);
        } else {
            echo "<br>No se ha encontrado el cliente o el soporte.";
        }
        return $this;
    }
}