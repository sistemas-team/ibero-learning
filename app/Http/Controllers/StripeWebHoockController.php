<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Cashier\Http\Controllers\WebhookController;
use Log;
class StripeWebHoockController extends Controller
{
    public function handleChargeSucceeded($payload)
    {
        try {
            $invoice_id = $payload['data']['object']["invoice"];
            $user = $this->getUserByStripeId($payload['data']['object']['customer']);
            if ($user) {
                $order = $user->orders()
                    ->where("status", Order::PENDING)
                    ->latest()
                    ->first();
                if ($order) {
                    $order->update([
                        'invoice_id' => $invoice_id,
                        'status' => Order::SUCCESS
                    ]);

                    // ATTACH COURSES FOR USER
                    $coursesId = $order->order_lines()->pluck("course_id");
                    Log::info(json_encode($coursesId));
                    $user->courses_learning()->attach($coursesId);

                    Log::info(json_encode($user));
                    Log::info(json_encode($order));
                    Log::info("Pedido actualizado correctamente");
                    return new Response('Webhook Handled: {handleChargeSucceeded}', 200);
                }
            }
        } catch (\Exception $exception) {
            Log::debug("Excepción Webhook {handleChargeSucceeded}: " . $exception->getMessage() . ", Line: " . $exception->getLine() . ', File: ' . $exception->getFile());
            return new Response('Webhook Handled with error: {handleChargeSucceeded}', 400);
        }
    }
}
