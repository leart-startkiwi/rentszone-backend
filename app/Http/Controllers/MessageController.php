<?php

namespace App\Http\Controllers;

use Botble\Base\Facades\EmailHandler;
use Botble\Base\Rules\EmailRule;
use Botble\CarRentals\Models\Car;
use Botble\CarRentals\Models\Customer;
use Botble\CarRentals\Models\Message;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class MessageController extends Controller
{
    public function getConversationDetails(Request $request)
    {
        if (!auth('sanctum')->check()) {
            return response()->json([
                'message' => 'Unauthenticated. Please login first.',
            ], 401);
        }

        $user = auth('sanctum')->user();
        $customerId = $request->input('customer_id');
        $vendorId = $request->input('vendor_id');
        $carId = $request->input('car_id');

        if (!$customerId || !$vendorId || !$carId) {
            return response()->json([
                'message' => 'customer_id, vendor_id, and car_id are required.'
            ], 400);
        }

        $messages = Message::with(['car', 'customer'])
            ->where('customer_id', $customerId)
            ->where('vendor_id', $vendorId)
            ->where('car_id', $carId)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function($msg) use ($customerId, $vendorId) {
                return [
                    'id' => $msg->id,
                    'content' => $msg->content,
                    'created_at' => $msg->created_at,
                    'sender' => $msg->last_sender_id == $customerId ? 'customer' : ($msg->last_sender_id == $vendorId ? 'vendor' : 'other'),
                    'read_status' => $msg->status,
                ];
            });

        // Fetch car, customer, vendor
        $car = \Botble\CarRentals\Models\Car::find($carId);
        $customer = \Botble\CarRentals\Models\Customer::find($customerId);
        $vendor = \Botble\CarRentals\Models\Customer::find($vendorId);

        // Car details
        $carName = $car ? $car->name : null;
        $carRentalRate = $car ? $car->rental_rate : null;
        $carImages = $car && $car->images ? (is_array($car->images) ? $car->images : json_decode($car->images, true)) : [];
        $carFirstImage = (is_array($carImages) && count($carImages) > 0) ? reset($carImages) : null;

        // Customer details
        $customerName = $customer ? $customer->name : null;
        $customerAvatar = $customer ? ($customer->avatar ?? ($customer->avatar ?? null)) : null;

        // Vendor details
        $vendorName = $vendor ? $vendor->name : null;
        $vendorAvatar = $vendor ? ($vendor->avatar ?? ($vendor->avatar ?? null)) : null;

        return response()->json([
            'conversation' => [
                'customer_id' => $customerId,
                'vendor_id' => $vendorId,
                'car_id' => $carId,
                'car_name' => $carName,
                'car_rental_rate' => $carRentalRate,
                'car_first_image' => $carFirstImage,
                'vendor_name' => $vendorName,
                'vendor_avatar' => $vendorAvatar,
                'customer_name' => $customerName,
                'customer_avatar' => $customerAvatar,
                'messages' => $messages,
            ]
        ]);
    }
    public function getUnreadMessagesCount(Request $request)
    {
        if (!auth('sanctum')->check()) {
            return response()->json([
                'message' => 'Unauthenticated. Please login first.',
            ], 401);
        }

        $user = auth('sanctum')->user();
        $userId = $user->id;


        $unreadCount = \Botble\CarRentals\Models\Message::where('status', 'unread')
            ->where('last_sender_id', '!=', $userId)
            ->count();

        return response()->json([
            'unread_count' => $unreadCount,
        ]);
    }
    public function sendMessage(string $id, Request $request)
    {
        // Validation rules from MessageRequest
        $rules = [
            'content' => ['required', 'string', 'max:1000'],
        ];

        if (!auth('sanctum')->check()) {
            $rules += [
                'name' => ['required', 'string', 'max:120'],
                'email' => ['required', new EmailRule()],
            ];
        }

        $user = auth('sanctum')->user();

        // If the current user is a vendor (matches car->vendor_id), require customer_id in request
        $car = Car::query()->findOrFail($id);
        $isVendor = $user && $car && $car->vendor_id == $user->id;
        if ($isVendor) {
            $rules['customer_id'] = ['required', 'integer', 'exists:cr_customers,id'];
        }

        $validated = $request->validate($rules);

        try {
            $link = $car->url;
            $subject = $car->name;

            $sendTo = null;
            if ($car->author->email) {
                $sendTo = $car->author->email;
            }

            $data = [
                ...$validated,
                'car_id' => $id, // Add car_id from route parameter
            ];

            if ($user) {
                $data['name'] = $user->name;
                $data['email'] = $user->email;
                $data['phone'] = $user->phone;
                // If vendor, use provided customer_id; if customer, use own id
                if ($isVendor) {
                    $data['customer_id'] = $request->input('customer_id');
                } else {
                    $data['customer_id'] = $user->id;
                }
            }

            $message = new Message();
            $message->fill($data);
            $message->car_id = (int) $id;
            $message->vendor_id = $car->vendor_id ? $car->vendor_id : null;
            $message->last_sender_id = $user->id;
            $message->save();

            EmailHandler::setModule(CAR_RENTALS_MODULE_SCREEN_NAME)
                ->setVariableValues([
                    'message_name' => $message->name,
                    'message_email' => $message->email,
                    'message_phone' => $message->phone,
                    'message_content' => $message->content,
                    'message_link' => $link,
                    'message_subject' => $subject,
                ])
                ->sendUsingTemplate('message', $sendTo);

            return response()->json([
                'message' => 'Send message successfully!',
            ]);

        } catch (Exception $exception) {
            $message = "Can't send message at this time, please try again later!";

            if (App::isLocal() && App::hasDebugModeEnabled()) {
                $message = $exception->getMessage();
            }

            return response()->json([
                'message' => $message,
            ], 500);
        }
    }


    public function getConversations(Request $request)
    {
        try {
            if (!auth('sanctum')->check()) {
                return response()->json([
                    'message' => 'Unauthenticated. Please login first.',
                ], 401);
            }

            $user = auth('sanctum')->user();

            // Get all messages for this user (as vendor or customer)
            $allMessages = Message::with(['car', 'car.author', 'customer'])
                ->where(function($q) use ($user) {
                    $q->where('vendor_id', $user->id)
                      ->orWhere('customer_id', $user->id);
                })
                ->orderBy('created_at', 'desc')
                ->get();

            // Group by conversation (vendor_id, customer_id, car_id)
            $currentUserId = $user->id;
            $conversations = $allMessages->groupBy(function($msg) {
                return $msg->vendor_id . '-' . $msg->customer_id . '-' . $msg->car_id;
            })->map(function($msgs) use ($currentUserId) {
                $lastMsg = $msgs->sortByDesc('created_at')->first();
                $car = $lastMsg->car;
                $customer = $lastMsg->customer;
                // Get vendor as a customer record
                $vendor = $lastMsg->vendor_id ? \Botble\CarRentals\Models\Customer::find($lastMsg->vendor_id) : null;

                $firstImage = null;
                if ($car && $car->images) {
                    $images = is_string($car->images) ? json_decode($car->images, true) : $car->images;
                    if (is_array($images) && count($images) > 0) {
                        $firstImage = reset($images);
                    }
                }

                // If last_sender_id is me, status is 'read', else fetch real status from DB
                if ($lastMsg->last_sender_id == $currentUserId) {
                    $statusValue = 'read';
                } else {
                    // Fetch the real status from DB in case it's an enum object
                    $dbMsg = \Botble\CarRentals\Models\Message::find($lastMsg->id);
                    $statusValue = $dbMsg && method_exists($dbMsg->status, 'value') ? $dbMsg->status->value : (string)($dbMsg->status ?? $lastMsg->status);
                }

                return [
                    'car_id' => $lastMsg->car_id,
                    'vendor_id' => $lastMsg->vendor_id,
                    'customer_id' => $lastMsg->customer_id,
                    'last_message' => $lastMsg->content,
                    'last_message_date' => $lastMsg->created_at,
                    'status' => $statusValue,
                    'vendor_name' => $vendor ? $vendor->name : null,
                    'customer_name' => $customer ? $customer->name : null,
                    'car_name' => $car ? $car->name : null,
                    'car_image' => $firstImage,
                ];
            })->values();

            return response()->json([
                'message' => 'Conversations retrieved successfully!',
                'conversations' => $conversations,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve conversations',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

public function markAsRead($id, Request $request)
{
    try {
        // Check if user is authenticated
        if (!auth('sanctum')->check()) {
            return response()->json([
                'message' => 'Unauthenticated. Please login first.',
            ], 401);
        }
        
        $user = auth('sanctum')->user();
        
        // Find the message
        $message = Message::findOrFail($id);
        
        // Check if the user is authorized to mark this message as read
        // Only the vendor who received the message or customer who sent it can mark it as read
        if ($message->vendor_id !== $user->id && $message->customer_id !== $user->id) {
            return response()->json([
                'message' => 'Unauthorized. You can only mark your own messages as read.',
            ], 403);
        }
        
        // Update the message status to 'read'
        $message->status = 'read';
        $message->save();
        
        return response()->json([
            'message' => 'Message marked as read successfully!',
            'data' => [
                'id' => $message->id,
                'status' => $message->status,
                'updated_at' => $message->updated_at,
            ],
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to mark message as read',
            'error' => $e->getMessage(),
        ], 500);
    }
}
}