<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Http\Resources\OrderResource;
use SoapClient;
use SimpleXMLElement;

class OrderController extends Controller
{


    function logArray($data, $prefix = '') {
        foreach ($data as $key => $value) {
            if (is_array($value) || is_object($value)) {
                $this->logArray($value, $prefix . $key . '.');
            } else {
                error_log($prefix . $key . ': ' . $value);
            }
        }
    }


    public function getOrderById($id, Request $request)
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
            return null;
        }

        if(count($xmlObject)==0){
            return response()->json(['error' => 'Order not found'], 404);
        }

        $data = json_decode(json_encode($xmlObject), true);
        error_log((string)$data['order']['id']);
        $res = new OrderResource(collect($data['order']));
        $array = $res->toArrayById($request);


        return response()->json(['order' => $array]);


    }

    public function getAll(Request $request)
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
            return null;
        }

        $data = json_decode(json_encode($xmlObject), true);

       $res = new OrderResource(collect($data['order']));
        $array = $res->toArray($request);

        return response()->json(['orders' => $array]);
    }
}
