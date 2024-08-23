<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;




class OrderResource extends JsonResource
{
    public function processProducts($order): int
    {
        if (isset($order['products'])) {
            $productCount = count($order['products']);
            return $productCount;
        }
        return 0;
    }

    public function processProduct($order)
    {
        $products = [];
        foreach ($order['products'] as $product) {
            $products[] = [
                'code' => (string) $product['code'],
                'quantity' => (int) $product['quantity'],
                'images' => isset($product['images']) ? (array) $product['images'] : [],
            ];
        }
        return $products;
    }

    public function toArrayById($request): array
    {

        $order = [
            'id' => (string)$this['id'],
            'external_id' => isset($this['externalId']) ? (string) $this['externalId'] : null,
            'confirmed' => (string)$this['confirmed'] === '1' ? 'True' : 'False',
            'shipping_method' => isset($this['shippingMethod']) ? (string) $this['shippingMethod'] : null,
            'total_products' => $this->processProducts($this),
            'shipping_first_name' => isset($this['client']['shippingFirstName']) ? (string)$this['client']['shippingFirstName'] : null,
            'shipping_last_name'=> isset($this['client']['shippingLastName']) ? (string)$this['client']['shippingLastName'] : null,
            'shipping_company'=> isset($this['client']['shippingCompany']) ? (string)$this['client']['shippingCompany'] : null,
            'shipping_street'=> isset($this['client']['shippingStreet']) ? (string)$this['client']['shippingStreet'] : null,
            'shipping_street_number_1'=> isset($this['client']['shippingStreetNumber1']) ? $this['client']['shippingStreetNumber1'] : null,
            'shipping_street_number_2'=> isset($this['client']['shippingStreetNumber2']) ?  $this['client']['shippingStreetNumber2'] : null,
            'shipping_post_code'=> isset($this['client']['shippingPostCode']) ? (string)$this['client']['shippingPostCode'] : null,
            'shipping_city'=> isset($this['client']['shippingCity']) ? (string)$this['client']['shippingCity'] : null,
            'shipping_state_code'=> isset($this['client']['shippingStateCode']) ? (string)$this['client']['shippingStateCode'] : null,
            'shipping_state'=> isset($this['client']['shippingState']) ? (string)$this['client']['shippingState'] : null,
            'shipping_country_code'=> isset($this['client']['shippingCountryCode']) ? (string)$this['client']['shippingCountryCode'] : null,
            'shipping_country'=> isset($this['client']['shippingCountry']) ? (string)$this['client']['shippingCountry'] : null,
        ];
        if($order['total_products']>0){
            $order['products'] = $this->processProduct($this);
        }
        else{
            $order['products'] = null;
        }

        if ($request->user()->type === 1) {
            $order['currency'] = isset($this['currency']) ? (string)$this['currency'] : null;
            $order['order_sum'] = isset($this['order_sum']) ? (float)$this['order_sum'] : 0.0;
            $order['paid'] = isset($this['paid']) ? (float)$this['paid'] : 0.0;
            $order['username'] = isset($this['client']['username']) ? (string)$this['client']['username'] : null;
        }

        return $order;
    }

    public function toArray($request): array
    {
        error_log("test");
//        $client = new SoapClient('http://unlimitech.atomstore.pl/atom_api/wsdl/atom_api');
//        $authenticate = array('login' => 'backdev-konrad', 'password' => 'nJqvXk4qEaUdIEo.22');
//        header('Content-Type: application/xml');
//        $response = $client->GetOrders($authenticate);
//        $xml = simplexml_load_string($response);

//        return view('dashboard', ['orders' => $orders]);
        $orders = [];
        error_log("test2");
        error_log((string)$this[2]['id']);
        error_log("test33");

        for ($i = 0; $i < $this->count(); $i++) {
            $processedOrder = [
                'id' => (string)$this[$i]['id'],
                'external_id' => isset($this[$i]['externalId']) ? (string) $this[$i]['externalId'] : null,
                'confirmed' => (string)$this[$i]['confirmed'] === '1' ? 'True' : 'False',
                'shipping_method' => isset($this[$i]['shippingMethod']) ? (string) $this[$i]['shippingMethod'] : null,
                'total_products' => $this->processProducts($this[$i]),
            ];
            if($processedOrder['total_products']>0){
                $processedOrder['products'] = $this->processProduct($this[$i]);
            }
            else{
                $processedOrder['products'] = null;
            }

            if ($request->user()->type === 1) {
                $processedOrder['currency'] = isset($this[$i]['currency']) ? (string)$this[$i]['currency'] : null;
                $processedOrder['order_sum'] = isset($this[$i]['order_sum']) ? (float)$this[$i]['order_sum'] : 0.0;
                $processedOrder['paid'] = isset($this[$i]['paid']) ? (float)$this[$i]['paid'] : 0.0;
                $processedOrder['username'] = isset($this[$i]['client']['username']) ? (string)$this[$i]['client']['username'] : null;
            }
            $orders[] = $processedOrder;
        }



        return $orders;

//        $data = [
//            'id' => $this->id,
//            'external_id' => $this->external_id,
//            'confirmed' => $this->confirmed,
//            'shipping_method' => $this->shippingMethod->name,
//            'total_products' => $this->products->sum('quantity'),
//            'products' => $this->products->map(function ($product) {
//                return [
//                    'code' => $product->code,
//                    'quantity' => $product->quantity,
//                    'images' => $product->images->pluck('url'),
//                ];
//            }),
//        ];
//
//        if ($request->user()->type === 1) {
//            $data += [
//                'currency' => $this->currency,
//                'order_sum' => $this->total_paid,
//                'paid' => $this->total_amount,
//                'username' => $this->customer->name,
//            ];
//        }
//
//        return $data;
    }






//    private function processOrders($xml)
//    {
//        $orders = [];
//        foreach ($xml->order as $order) {
//            $processedOrder = [
//                'id' => (string)$order->id,
//                'external_id' => (string)$order->externalId,
//                'confirmed' => (string)$order->confirmed === '1' ? 'Yes' : 'No',
//                'shipping_method' => (string)$order->shippingMethod,
//                'total_products' => 0, // You'll need to calculate this from the products if available
//                'currency' => (string)$order->currency,
//                'order_sum' => (float)$order->order_sum,
//                'paid' => (float)$order->paid,
//                'username' => (string)$order->client->firstname . ' ' . (string)$order->client->lastname,
//            ];
//            $orders[] = $processedOrder;
//        }
//        return $orders;
//    }
}
