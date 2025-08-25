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

        $validated = $request->validate($rules);

        try {
            $car = Car::query()->findOrFail($id);

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

            if (auth('sanctum')->check()) {
                $customer = auth('sanctum')->user();

                $data['name'] = $customer->name;
                $data['email'] = $customer->email;
                $data['phone'] = $customer->phone;
                $data['customer_id'] = $customer->id; // Add customer_id from authenticated user
            }

            $message = new Message();
            $message->fill($data);
            $message->car_id = (int) $id; 
            $message->vendor_id = $car->vendor_id ? $car->vendor_id : null;
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


public function getMessages(Request $request)
{
    try {
        // Check if user is authenticated
        if (!auth('sanctum')->check()) {
            return response()->json([
                'message' => 'Unauthenticated. Please login first.',
            ], 401);
        }
        
        $user = auth('sanctum')->user();
        
        // Get messages for this user (either as vendor receiving messages or customer who sent messages)
        $query = Message::with(['car'])
            ->where(function($q) use ($user) {
                $q->where('vendor_id', $user->id)      // Messages received as vendor
                  ->orWhere('customer_id', $user->id); // Messages sent as customer
            })
            ->orderBy('created_at', 'desc');
        
        // Optional: Filter by message type
        if ($request->has('message_type')) {
            $messageType = $request->input('message_type');
            if ($messageType === 'received') {
                // Messages received as vendor
                $query->where('vendor_id', $user->id);
            } elseif ($messageType === 'sent') {
                // Messages sent as customer
                $query->where('customer_id', $user->id);
            }
        }
        
        // Optional: Filter by read/unread status
        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }
        
        // Optional: Filter by car_id
        if ($request->has('car_id')) {
            $query->where('car_id', $request->input('car_id'));
        }
        
        // Optional: Search in message content or sender name
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('content', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Pagination
        $perPage = $request->input('per_page', 15);
        $messages = $query->paginate($perPage);
        
        return response()->json([
            'message' => 'Messages retrieved successfully!',
            'messages' => $messages->items(),
            'pagination' => [
                'current_page' => $messages->currentPage(),
                'last_page' => $messages->lastPage(),
                'per_page' => $messages->perPage(),
                'total' => $messages->total(),
            ],
            'counts' => [
                'total_received' => Message::where('vendor_id', $user->id)->count(),
                'total_sent' => Message::where('customer_id', $user->id)->count(),
                'unread_received' => Message::where('vendor_id', $user->id)
                    ->where('status', 'unread')->count(),
            ],
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to retrieve messages',
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