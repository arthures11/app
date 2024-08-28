<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Http\Resources\OrderResource;
use App\Http\Services\OrderService;
use SoapClient;
use SimpleXMLElement;
use Exception;

/**
 * @OA\Info(
 *     title="Orders API",
 *     version="1.0.0",
 *     description="simple api for retrieving all orders and specific order information"
 * )
 */

class OrderController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/orders/{id}",
     *     summary="Get order by ID",
     *     tags={"Orders"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Order ID",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="order",
     *                 type="object",
     *                 @OA\Property(property="id", type="string"),
     *                 @OA\Property(property="external_id", type="string", nullable=true),
     *                 @OA\Property(property="confirmed", type="string", enum={"True", "False"}),
     *                 @OA\Property(property="shipping_method", type="string", nullable=true),
     *                 @OA\Property(property="total_products", type="integer"),
     *                 @OA\Property(property="shipping_first_name", type="string", nullable=true),
     *                 @OA\Property(property="shipping_last_name", type="string", nullable=true),
     *                 @OA\Property(property="shipping_company", type="string", nullable=true),
     *                 @OA\Property(property="shipping_street", type="string", nullable=true),
     *                 @OA\Property(property="shipping_street_number_1", type="string", nullable=true),
     *                 @OA\Property(property="shipping_street_number_2", type="string", nullable=true),
     *                 @OA\Property(property="shipping_post_code", type="string", nullable=true),
     *                 @OA\Property(property="shipping_city", type="string", nullable=true),
     *                 @OA\Property(property="shipping_state_code", type="string", nullable=true),
     *                 @OA\Property(property="shipping_state", type="string", nullable=true),
     *                 @OA\Property(property="shipping_country_code", type="string", nullable=true),
     *                 @OA\Property(property="shipping_country", type="string", nullable=true),
     *                 @OA\Property(property="products", type="array", @OA\Items(type="object"), nullable=true),
     *                 @OA\Property(property="currency", type="string", nullable=true),
     *                 @OA\Property(property="order_sum", type="number", format="float"),
     *                 @OA\Property(property="paid", type="number", format="float"),
     *                 @OA\Property(property="username", type="string", nullable=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order not found"
     *     )
     * )
     */
    public function getOrderById($id, Request $request)
    {
        try{
        $service = new OrderService();

        $data = $service->getOrderById($id);

        //error_log((string)$data['order']['id']);
        $res = new OrderResource(collect($data['order']));
        $array = $res->toArrayById($request);


        return response()->json(['order' => $array]);
        }
        catch (Exception $e) {
            error_log($e);
            //    return redirect()->back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);

            return response()->json([
                'error' => 'Something went wrong',
                'message' => $e->getMessage(),
            ], 500);

        }

    }

    /**
     * @OA\Get(
     *     path="/api/v1/orders",
     *     summary="Get all orders",
     *     tags={"Orders"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="page number",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="number of items in page",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="orders",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="string"),
     *                     @OA\Property(property="external_id", type="string", nullable=true),
     *                     @OA\Property(property="confirmed", type="string", enum={"True", "False"}),
     *                     @OA\Property(property="shipping_method", type="string", nullable=true),
     *                     @OA\Property(property="total_products", type="integer"),
     *                     @OA\Property(property="products", type="array", @OA\Items(type="object"), nullable=true),
     *                     @OA\Property(property="currency", type="string", nullable=true),
     *                     @OA\Property(property="order_sum", type="number", format="float"),
     *                     @OA\Property(property="paid", type="number", format="float"),
     *                     @OA\Property(property="username", type="string", nullable=true)
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer"),
     *                 @OA\Property(property="per_page", type="integer"),
     *                 @OA\Property(property="total", type="integer"),
     *                 @OA\Property(property="total_pages", type="integer")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No orders have been found."
     *     )
     * )
     */
    public function getAll(Request $request)
    {
        try {
            $page = $request->query('page');
            $perPage = $request->query('per_page', 5);
            error_log("dupa $perPage");

            $service = new OrderService();

            if ($page != null) {
                $data = $service->getAllPaged($page, $perPage);
                //error_log($data['order'][0]['id']);
                $res = new OrderResource(collect($data['order']));

                $array = $res->toArray($request);

                $result = [
                    'orders' => $array,
                ];
                $totalOrders = count($data['order']);
                $totalPages = ceil($totalOrders / $perPage);

                $result['meta'] = [
                    'current_page' => (int)$page,
                    'per_page' => (int)$perPage,
                    'total' => $totalOrders,
                    'total_pages' => $totalPages,
                ];

                return response()->json($result);
            } else {
                $data = $service->getAll();
                $res = new OrderResource(collect($data['order']));

                $array = $res->toArray($request);

                return response()->json(['orders' => $array]);
            }
        }
         catch (Exception $e) {
            error_log($e);
         //    return redirect()->back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);

             return response()->json([
                 'error' => 'Something went wrong',
                 'message' => $e->getMessage(),
             ], 500);

         }


    }
}
