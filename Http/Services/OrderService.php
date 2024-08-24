<?php
namespace App\Http\Services;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Http\Resources\OrderResource;
use SoapClient;
use SimpleXMLElement;

/**
 * @OA\Info(
 *     title="Orders API",
 *     version="1.0.0",
 *     description="simple api for retrieving all orders and specific order information"
 * )
 */

class OrderService
{

    public function getOrderById($id)
    {

        $client = new SoapClient('http://unlimitech.atomstore.pl/atom_api/wsdl/atom_api');
        $authenticate = array('login' => 'backdev-konrad', 'password' => 'nJqvXk4qEaUdIEo.22');
        $response = $client->GetOrdersSpecified($authenticate, '', 0, 0, 0, '', '', "id|$id");
        header('Content-Type: application/xml');

        if ($response instanceof \SimpleXMLElement) {
            $responseArray = json_decode(json_encode($response), true);
        } else {
            $responseArray = (array) $response;
        }
        $xmlObject = simplexml_load_string($responseArray[0], "SimpleXMLElement", LIBXML_NOCDATA);

        if ($xmlObject === false) {
            error_log('Failed to parse XML string');
            return response()->json(['error' => 'Something went wrong'], 404);
        }

        if(count($xmlObject)==0){
            return response()->json(['error' => 'Order not found'], 404);
        }

        $data = json_decode(json_encode($xmlObject), true);
        error_log((string)$data['order']['id']);


        return $data;


    }

    public function getAll()
    {
        $client = new SoapClient('http://unlimitech.atomstore.pl/atom_api/wsdl/atom_api');
        $authenticate = array('login' => 'backdev-konrad', 'password' => 'nJqvXk4qEaUdIEo.22');
        header('Content-Type: application/xml');

        $response = $client->GetOrders($authenticate);

        if ($response instanceof \SimpleXMLElement) {
            $responseArray = json_decode(json_encode($response), true);
        } else {
            $responseArray = (array) $response;
        }


        $xmlObject = simplexml_load_string($responseArray[0], "SimpleXMLElement", LIBXML_NOCDATA);

        if ($xmlObject === false) {
            error_log('Failed to parse XML string');
            return response()->json(['error' => 'Something went wrong'], 404);
        }

        if(count($xmlObject)==0){
            return response()->json(['error' => 'Order not found'], 404);
        }

        $data = json_decode(json_encode($xmlObject), true);


        return $data;
    }

}
