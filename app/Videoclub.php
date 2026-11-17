<?php
namespace Dwes\ProyectoVideoclub;
use Dwes\ProyectoVideoclub\Util\VideoclubException;
use Dwes\ProyectoVideoclub\Util\SoporteNoEncontradoException;
use Dwes\ProyectoVideoclub\Util\ClienteNoEncontradoException;
use Dwes\ProyectoVideoclub\Util\SoporteYaAlquiladoException;
class Videoclub {
    private $nombre;
    private $productos=[];
    private $numProductos=0;
    private $socios=[];
    private $numSocios=0;
    private $numProductosAlquilados = 0;
    private $numTotalAlquileres = 0;

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

    public function getNumProductosAlquilados(): int
    {
        return $this->numProductosAlquilados;
    }

    public function getNumTotalAlquileres(): int
    {
        return $this->numTotalAlquileres;
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

    public function alquilarSocioProducto($numeroCliente, $numeroSoporte): self
    {
        try
        {
            $cliente = $this->buscarSocio($numeroCliente);
            $soporte = $this->buscarProducto($numeroSoporte);
            $cliente->alquilar($soporte);

            $this->numProductosAlquilados++;
            $this->numTotalAlquileres++;
            echo "<br>Soporte " . $numeroSoporte . " alquilado al socio " . $numeroCliente;
        }
        catch (VideoclubException $e)
        {
            echo "<br>" . $e->getMessage();
        }
        return $this;
    }

    private function buscarSocio(int $numero): Cliente
    {
        foreach ($this->socios as $socio)
        {
            if ($socio->getNumero() === $numero)
            {
                return $socio;
            }
        }
        throw new ClienteNoEncontradoException("No existe el socio " . $numero);
    }

    private function buscarProducto(int $numero): Soporte
    {
        foreach ($this->productos as $producto)
        {
            if ($producto->getNumero() === $numero)
            {
                return $producto;
            }
        }
        throw new SoporteNoEncontradoException("No existe el producto " . $numero);
    }

    public function alquilarSocioProductos(int $numSocio, array $numerosProductos): self
    {
        try
        {
            $this->buscarSocio($numSocio);

            foreach ($numerosProductos as $numero)
            {
                $soporte = $this->buscarProducto($numero);
                if ($soporte->alquilado)
                {
                    throw new SoporteYaAlquiladoException("El soporte " . $numero . " no está disponible. No se alquila ninguno");
                }
            }

            foreach ($numerosProductos as $numero)
            {
                $this->alquilaSocioProducto($numSocio, $numero);
            }
        }
        catch (VideoclubException $e)
        {
            echo "<br>" . $e->getMessage();
        }
        return $this;
    }

    public function devolverSocioProducto(int $numSocio, int $numeroProducto): self
    {
        try
        {
            $cliente = $this->buscarSocio($numSocio);
            $cliente->devolver($numeroProducto);
            $this->numProductosAlquilados--;
            echo "<br>Soporte " . $numeroProducto . " devuelto por el socio " . $numSocio;
        }
        catch (VideoclubException $e)
        {
            echo "<br>" . $e->getMessage();
        }
        return $this;
    }

    public function devolverSocioProductos(int $numSocio, array $numerosProductos): self
    {
        foreach ($numerosProductos as $numero)
        {
            $this->devolverSocioProducto($numSocio, $numero);
        }
        return $this;
    }
}