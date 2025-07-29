<?php

namespace Botble\CarRentals\Http\Controllers\Fronts;

use Botble\Base\Facades\EmailHandler;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\CarRentals\Http\Requests\Fronts\MessageRequest;
use Botble\CarRentals\Models\Car;
use Botble\CarRentals\Models\Customer;
use Botble\CarRentals\Models\Message;
use Exception;
use Illuminate\Support\Facades\App;

class MessageController extends BaseController
{
    public function store(string $id, MessageRequest $request): BaseHttpResponse
    {
        try {
            $car = Car::query()->findOrFail($id);

            $link = $car->url;
            $subject = $car->name;

            $sendTo = null;

            if ($car->author->email) {
                $sendTo = $car->author->email;
            }

            $data = [
                ...$request->input(),
                'ip_address' => $request->ip(),
            ];

            if (auth('customer')->check()) {
                $customer = auth('customer')->user();

                $data['name'] = $customer->name;
                $data['email'] = $customer->email;
                $data['phone'] = $customer->phone;
            }

            $message = new Message();
            $message->fill($data);
            $message->vendor_id = $car->author_type == Customer::class && $car->author->is_vendor ? $car->author_id : null;

            EmailHandler::setModule(CAR_RENTALS_MODULE_SCREEN_NAME)
                ->setVariableValues([
                    'message_name' => $message->name,
                    'message_email' => $message->email,
                    'message_phone' => $message->phone,
                    'message_content' => $message->content,
                    'message_link' => $link,
                    'message_subject' => $subject,
                    'message_ip_address' => $message->ip_address,
                ])
                ->sendUsingTemplate('message', $sendTo);

            return $this
                ->httpResponse()
                ->setMessage(__('Send message successfully!'));
        } catch (Exception $exception) {
            $message = __("Can't send message at this time, please try again later!");

            if (App::isLocal() && App::hasDebugModeEnabled()) {
                $message = $exception->getMessage();
            }

            return $this
                ->httpResponse()
                ->setError()
                ->setMessage($message);
        }
    }
}
