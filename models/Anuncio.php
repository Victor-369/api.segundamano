<?php
    class Anuncio extends Model {
        public function validate():array {
            $errores = [];

            if(strlen($this->titulo) < 1 || strlen($this->titulo) > 64) {
                $errores[] = "Error en la longitud del título";
            }

            if(strlen($this->descripcion) < 1 || strlen($this->descripcion) > 101) {
                $errores[] = "Error en la longitud de la descripción";
            }

            if(!floatval($this->precio)) {
                $errores[] = "El precio debe ser numérico.";
            }

            return $errores;
        }
    }