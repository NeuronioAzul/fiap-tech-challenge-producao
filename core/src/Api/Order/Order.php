<?php

namespace TechChallenge\Api\Order;

use TechChallenge\Api\Controller;
use Illuminate\Http\Request;
use Throwable;
use TechChallenge\Domain\Shared\Exceptions\DefaultException;
use TechChallenge\Adapters\Controllers\Order\ChangeStatus as ControllerOrderChangeStatus;
use TechChallenge\Adapters\Controllers\Order\Checkout as ControllerOrderCheckout;
use TechChallenge\Adapters\Controllers\Order\Webhook as ControllerOrderWebhook;

class Order extends Controller
{    
    public function checkout(Request $request, string $id)
    {
        try {
            (new ControllerOrderCheckout($this->AbstractFactoryRepository))->execute($id);

            return $this->return(null, 204);
        } catch (DefaultException $e) {
            return $this->return(
                [
                    "error" => [
                        "message" => $e->getMessage()
                    ]
                ],
                $e->getStatus()
            );
        } catch (Throwable $e) {
            return $this->return(
                [
                    "error" => [
                        "message" => $e->getMessage()
                    ]
                ],
                400
            );
        }
    }

    public function changeStatus(Request $request, string $id)
    {
        try {
            (new ControllerOrderChangeStatus($this->AbstractFactoryRepository))
                ->execute(
                    $id,
                    $request->get('status')
                );

            return $this->return(null, 204);
        } catch (DefaultException $e) {
            return $this->return(
                [
                    "error" => [
                        "message" => $e->getMessage()
                    ]
                ],
                $e->getStatus()
            );
        } catch (Throwable $e) {
            return $this->return(
                [
                    "error" => [
                        "message" => $e->getMessage()
                    ]
                ],
                400
            );
        }
    }

    public function webhook(Request $request)
    {
        try {
            (new ControllerOrderWebhook($this->AbstractFactoryRepository))
                ->execute($request->get('id'));

            return $this->return(null, 204);
        } catch (DefaultException $e) {
            return $this->return(
                [
                    "error" => [
                        "message" => $e->getMessage()
                    ]
                ],
                $e->getStatus()
            );
        } catch (Throwable $e) {
            return $this->return(
                [
                    "error" => [
                        "message" => $e->getMessage()
                    ]
                ],
                400
            );
        }
    }
}
