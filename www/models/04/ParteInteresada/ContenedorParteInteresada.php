<?php

    namespace ModelParteInteresada;
    use \ModelGeneral\ActiveRecord as ActiveRecord;

    //Definimos la clase
    class ContenedorParteInteresada extends ActiveRecord{

        //region PROPIEDADES
            public $id;
            public $codigo_interno;
            public $denominador;
            public $comentarios;

        //endregion

        //region BASE DE DATOS

            protected static $db;
            protected static $tabla = '04_partesinteresadas_contenedor';
            protected static $columnasDB = ['id', 'codigo_interno', 'denominador', 'comentarios'];    

        //endregion

        //region CONSTRUCTOR

            //Definimos el constructor y las propiedades del mismo
                public function __construct($args = []){

                    $this->id = $args['id'] ?? '';
                    $this->codigo_interno = $args['codigo_interno'] ?? '';
                    $this->denominador =  $args['denominador'] ?? '';
                    $this->comentarios =  $args['comentarios'] ?? '';

                }
        //endregion
        
        //region VALIDACIÓN

            //Definimos un método para la validación de datos
                public function validar(){

                    if(!$this->id){
                        $errores[] = "El campo ID es obligatorio";
                    }

                }

        //endregion

        //region OTROS MÉTODOS

         //Definimos el método para calcular códigos
            public function calcularCodigo(){

                //Declaramos el query
                    $query = "SELECT codigo_interno FROM " . static::$tabla . " ORDER BY codigo_interno ASC"; //Static se hereda
                    $result = static::consultarSQL($query);

                //Creamos una variable con el último elemento del array

                    if(!$result){
                        $codigo = "EPI-001";
                    }
                    else{

                            $ultimoCodigo = end($result);

                        //Extraemos el campo que queremos
                            $ultimoCodigo = $ultimoCodigo->codigo_interno;
                        
                        //Separamos el código en 2, el string y el int
                            $stringCodigo = preg_replace('/[0-9]/', '', $ultimoCodigo);
                            $valorCodigo = (int) filter_var($ultimoCodigo, FILTER_SANITIZE_NUMBER_INT);
                            
                        //El código sale negativo por el guión del código. Así que sanitizamos el resultado
                            $valorCodigo = ($valorCodigo - 2*$valorCodigo) + 1;

                        //Ahora lo formateamos para que tenga el formato "000"
                            $longitud = 3;
                            $valorFormateado = substr(str_repeat(0, $longitud).$valorCodigo, -$longitud);

                        //Ponemos el cálculo final
                            $codigo = strval($stringCodigo) . strval($valorFormateado);

                    }

                return $codigo;

            }
            
            public function cargarContenedorPI(){

                //Buscamos los contenedores
                    $query = 
                    " SELECT * " .
                    " FROM " . static::$tabla .
                    " ORDER BY codigo_interno ASC";

                    $result = static::consultarSQL($query);

                return $result;
            }

        //endregion
    }

?>