<?php
namespace App\Http\Services;

use App\Exceptions\OrderNotFoundException;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Http\Resources\OrderResource;
use PHPUnit\Event\Code\Throwable;
use SoapClient;
use SimpleXMLElement;
use function Laravel\Prompts\error;


class OrderService
{

    /**
     * @throws OrderNotFoundException
     */
    public function getOrderById($id)
    {

        $client = new SoapClient('http://unlimitech.atomstore.pl/atom_api/wsdl/atom_api');
        $authenticate = array('login' => 'backdev-konrad', 'password' => 'nJqvXk4qEaUdIEo.22');
        $response = $client->GetOrdersSpecified($authenticate, '', 0, 0, 0, '', '', "id|$id");
        header('Content-Type: application/xml');

        error_log('here1');
        if ($response instanceof \SimpleXMLElement) {
            $responseArray = json_decode(json_encode($response), true);
        } else {
            $responseArray = (array) $response;
        }
        $xmlObject = simplexml_load_string($responseArray[0], "SimpleXMLElement", LIBXML_NOCDATA);

        error_log('here2');
        if ($xmlObject === false) {
            error_log('Failed to parse XML string');
            return response()->json(['error' => 'Something went wrong'], 404);
        }

        try {
           // error_log('here3');
           // error_log($xmlObject->count());
            //error_log(count($xmlObject));
            if(count($xmlObject)==0){
                throw new OrderNotFoundException("Invalid order ID", 404);
            }
            $data = json_decode(json_encode($xmlObject), true);

            //error_log($data->count());


        }
        catch (\Throwable $e) {
           // error_log('here EXCEPT');
            throw new OrderNotFoundException("Invalid order ID", 404);
        }
      //  error_log((string)$data['order']['id']);


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


    public function getAllPaged($page = null, $perPage = null)
    {
        $totalOrders = $page * $perPage;

        $client = new SoapClient('http://unlimitech.atomstore.pl/atom_api/wsdl/atom_api');
        $authenticate = array('login' => 'backdev-konrad', 'password' => 'nJqvXk4qEaUdIEo.22');
        $response = $client->GetOrdersSpecified($authenticate, '', 0, $totalOrders, 0, '', '', '');
        error_log("asdasdasd $totalOrders");
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

        if (isset($data['order']['id'])) {
            $data['order'] = [$data['order']];
        }

        if ($page !== null && $perPage !== null) {
            $offset = ($page - 1) * $perPage;
            $data['order'] = array_slice($data['order'], $offset, $perPage);
        }


        return $data;
    }

}
