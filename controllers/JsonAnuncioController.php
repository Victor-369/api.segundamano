<?php

    class JsonAnuncioController extends Controller{
        
        public function __construct(){
            header('Content-Type:application/json; charset=utf-8');
        }

        public function get($param1 = null, $param2 = null) {
            switch(true) {
                case $param1 && $param2:
                    $anuncios = Anuncio::getFiltered($param1, $param2);
                    break;
                
                case $param1 && !$param2:
                    if(!$anuncio = Anuncio::getById($param1)) {
                        http_response_code(404);
                        throw new ApiException("No se encontró el anuncio $param1");
                    }

                    $anuncios = [$anuncio];
                    break;

                default:
                    $anuncios = Anuncio::get();
                    break; // ???
            }

            $response = new stdClass();
            $response->status = "OK";
            $response->message = "Se han recuperado " . sizeof($anuncios) . " resultados.";
            $response->results = sizeof($anuncios);
            $response->data = $anuncios;

            echo JSON::encode($response);
        }

        public function delete(int $id = 0) {
            if(empty($id)) {
                throw new ApiException("No se indicó el anuncio a borrar.");
            }

            if(!$anuncio = Anuncio::getById($id)) {
                throw new ApiException("No existe el anuncio indicado.");
            }

            Anuncio::delete($id);

            $response = new stdClass();
            $response->status = "OK";
            $response->message = "Borrado del anuncio $anuncio->titulo correcto.";
            $response->data = [$anuncio];

            echo JSON::encode($response);
        }

        public function put() {
            $json = $this->request->body();

            if(empty($json)) {
                throw new ApiException("No se recibió el JSON con los anuncios a actualizar.");
            }

            $anuncios = JSON::decode($json, 'Anuncio');

            $response = new stdClass();
            $response->status = "OK";
            $response->message = "";
            $response->data = [];

            foreach($anuncios as $anuncio) {
                $anuncio->saneate();
                $errores = $anuncio->validate();

                if(sizeof($errores)) {
                    $response->status = "WARNING";
                    $response->message .= "$anuncio->titulo tiene errores.";
                    $response->data[$anuncio->titulo] = $errores;
                } else {
                    if(empty($anuncio->id)) {
                        $response->status = "WARNING";
                        $response->message .= "$anuncio->titulo no se puede actualizar.";
                        $response->data[$anuncio->titulo] = "No se indicó el ID a actualizar.";
                    } else {
                        try {
                            $anuncio->update();
                            $response->message .= "$anuncio->titulo actualizado correctamente.";
                        } catch(Throwable $t) {
                            $response->status = "WARNING";
                            $response->message .= "$anuncio->titulo no se pudo actualizar.";
                            $response->data[$anuncio->titulo] = DEBUG ? $t->getMessage() : "¿Duplicado?";
                        }
                    }
                }
            }

            echo JSON::encode($response);
        }

        public function post() {
            $json = $this->request->body();

            if(empty($json)) {
                throw new ApiException("No se indicaron anuncios a insertar.");
            }

            $anuncios = JSON::decode($json, 'Anuncio');

            $response = new stdClass();
            $response->status = "OK";
            $response->message = "";
            $response->data = [];

            foreach($anuncios as $anuncio) {
                $anuncio->saneate();
                $errores = $anuncio->validate();

                if(sizeof($errores)) {
                    $response->status = "WARNING";
                    $response->message .= "$anuncio->titulo tiene errores.";
                    $response->data[$anuncio->titulo] = $errores;
                } else {
                    try {
                        $anuncio->save();
                        $response->message .= "$anuncio->titulo guardado correctamente con ID $anuncio->id.";
                        http_response_code(201);
                    } catch(Throwable $t) {
                        $response->status = "WARNING";
                        $response->message .= "$anuncio->titulo no se pudo guardar.";
                        $response->data[$anuncio->titulo] = DEBUG ? $t->getMessage() : " ¿Duplicado?";
                    }
                }
            }

            echo JSON::encode($response);
        }

        
    }
