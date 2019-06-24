<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Planets;
use Symfony\Component\HttpFoundation\Response;
use TheSeer\Tokenizer\Exception;

class PlanetController extends Controller
{

    private $model;


    /**
     * @return void
     */
    public function __construct( Planets $planets )
    {
        $this->model = $planets;
    }

    /**
     * THe functions get all planets in database
     */
    public function getAll()
    {
        try {
            $planets = $this->model->all();

            if( count( $planets ) > 0 ){
                return response()->json( $planets, Response::HTTP_OK );
            }else{
                return response()->json( [], Response::HTTP_OK );
            }
            
        } catch (Exception $e) {
            return response()->json( [ 'Error' => $e ], Response::HTTP_INTERNAL_SERVER_ERROR );
        }

    }

    /**
     * Get planet by ID or Name, specify INT or String, the function compare and localize the type of value
     */
    public function getSingle( $term)
    {
        try {

            $planet = ( is_numeric( $term ) )
                ? $this->model->find( $term ) 
                : $this->model->where('nome', '=', $term)->get();


            if( !empty( $planet ) ){
                return response()->json( $planet, Response::HTTP_OK );
            }else{
                return response()->json( [], Response::HTTP_OK );
            }
            
        } catch (Exception $e) {
            return response()->json( [ 'Error' => $e ], Response::HTTP_INTERNAL_SERVER_ERROR );
        }
    }

    /**
     * Save a new planet in table on database
     */
    public function store( Request $request )
    {

        try {

            $response = $this->model->create( $request->all() );
            return response()->json( $response, Response::HTTP_CREATED );    
            
        } catch (Exception $e) {
            return response()->json( [ 'Error' => $e ], Response::HTTP_INTERNAL_SERVER_ERROR );
        }
    }

    
    /**
     * Delete a planet by id if exists
     * @Param Int $id
     */
    public function destroy( $id)
    {
        try {
            $this->model->find( $id )->delete();

            return response()->json ( NULL, Response::HTTP_OK );

        } catch (Exception $e) {
            return response()->json( [ 'Error' => $e ], Response::HTTP_INTERNAL_SERVER_ERROR );
        }
    }

    /**
     * This function get the json values from original SW API and insert in my database
     */
    public function converterJsonfile(){
        try {

            /**
             * Algoritmo para consumir a API e receber em $planets os dados dos planetas
             */
            $flag = 1;
            do{
                $cl = curl_init();
                curl_setopt($cl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($cl, CURLOPT_URL, "https://swapi.co/api/planets/?page=$flag");
                curl_setopt($cl, CURLOPT_HTTPHEADER, array("Content-Type: application/json;") );
                $compare =  json_decode( curl_exec($cl) );
                    foreach( $compare->results as $value ){
                        $planets[] = $value;
                    }
                $flag++;
                curl_close($cl);
            }while( NULL != $compare->next );

            //Com todos os planetas agora monto meus dados conforme o banco de dados e insiro no final.
            foreach( $planets as $planet ){

                $data_planet[] = array( 
                    'nome'          => $planet->name , 
                    'clima'         => $planet->climate, 
                    'terreno'       => $planet->terrain, 
                    'cnt_aparicoes' => count($planet->films), 
                );
            }

            //Insert com todos os dados
            $response = $this->model->insert( $data_planet );

            return response()->json( $response . ', todos os dados foram carregados e inseridos corretamente!', Response::HTTP_OK );
            
        } catch (Exception $e) {
            return response()->json( [ 'Error' => $e ], Response::HTTP_INTERNAL_SERVER_ERROR );
        }

    }

}
