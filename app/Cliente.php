<?php
namespace Dwes\ProyectoVideoclub;
use Dwes\ProyectoVideoclub\Util\SoporteYaAlquiladoException;
use Dwes\ProyectoVideoclub\Util\SoporteNoEncontradoException;
use Dwes\ProyectoVideoclub\Util\CupoSuperadoException;
 class Cliente {
     public string $nombre;
     private int $numero;
     private array $soportesAlquilados=[];
     private int $numSoportesAlquilados=0;
     private int $maxAlquilerConcurrente;

     /**
      * @param string $nombre
      * @param int $numero
      * @param int $maxAlquilerConcurrente
      */
     public function __construct(string $nombre, int $numero, int $maxAlquilerConcurrente=3)
     {
         $this->nombre = $nombre;
         $this->numero = $numero;
         $this->maxAlquilerConcurrente = $maxAlquilerConcurrente;
     }

     public function getNumero(): int
     {
         return $this->numero;
     }

     public function setNumero(int $numero): void
     {
         $this->numero = $numero;
     }

     public function getNumSoportesAlquilados(): int
     {
         return $this->numSoportesAlquilados;
     }

     public function tieneAlquilado(Soporte $s): bool
     {
         foreach ($this->soportesAlquilados as $SoporteAlquilado) {
            if ($SoporteAlquilado->getNumero() === $s->getNumero()) {
                return true;
            }
         }
         return false;
     }

     public function alquilar(Soporte $s): self
     {
         if ($s->alquilado)
         {
             throw new SoporteYaAlquiladoException("El soporte " . $s->getNumero() . " ya está alquilado");
         }
        if($this->soportesAlquilados>=$this->maxAlquilerConcurrente){
            throw new CupoSuperadoException("<br>No se puede igualar ni superar el cupo de alquileres.");
        }

        $this->soportesAlquilados[] = $s;
        $this->numSoportesAlquilados++;
        echo "Soporte: ".$s->getNumero()." alquilado<br>";
         $s->alquilado = true;
        return $this;
     }

     public function devolver(int $numSoporte): self
     {
         foreach ($this->soportesAlquilados as $indice => $alquilado) {
             if ($alquilado->getNumero() == $numSoporte) {
                 $alquilado->alquilado = false;
                 unset($this->soportesAlquilados[$indice]);
                 $this->soportesAlquilados = array_values($this->soportesAlquilados);
                 return $this;
             }
         }

         throw new SoporteNoEncontradoException("No tienes alquilado el soporte " . $numSoporte);
     }
     public function listaAlquileres(): void {
         echo "<br>El cliente " . $this->nombre . " tiene " . $this->numSoportesAlquilados . " alquileres:";
         foreach ($this->soportesAlquilados as $soporte) {
             $soporte->muestraResumen();
         }
     }

    public function muestraResumen():void
    {
        echo "<br>Nombre:".$this->nombre;
        echo "<br>Soportes alquilados: " . count($this->soportesAlquilados);
    }

 }