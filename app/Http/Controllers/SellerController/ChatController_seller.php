<?php

namespace App\Http\Controllers\SellerController;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\SellerModels\Chat_seller;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController_seller extends Controller
{
    public function index(Request $request)
    {
        $userId = \Illuminate\Support\Facades\Auth::id();
        $products = \App\Models\SellerModels\Product_seller::where('user_id', $userId)->get();
        $selectedProductId = $request->query('product');
        $selectedProduct = $selectedProductId ? $products->where('id', $selectedProductId)->first() : null;

        $conversationsQuery = Conversation::with(['buyer', 'product', 'latestMessage.sender'])
            ->where('seller_id', $userId)
            ->latest('updated_at');

        if ($selectedProduct) {
            $conversationsQuery->where('product_id', $selectedProduct->id);
        }

        $conversations = $conversationsQuery->get();

        $conversations->each(function ($conv) use ($userId) {
            $conv->unread_count = Message::where('conversation_id', $conv->id)
                ->where('sender_id', '!=', $userId)
                ->whereNull('read_at')
                ->count();
        });

        $productChatCount = Conversation::where('seller_id', $userId)
            ->selectRaw('product_id, count(*) as total')
            ->groupBy('product_id')
            ->pluck('total', 'product_id');

        $productUnreadCount = Message::whereHas('conversation', function ($q) use ($userId) {
                $q->where('seller_id', $userId);
            })
            ->where('sender_id', '!=', $userId)
            ->whereNull('read_at')
            ->join('conversations', 'messages.conversation_id', '=', 'conversations.id')
            ->selectRaw('conversations.product_id, count(messages.id) as total')
            ->groupBy('conversations.product_id')
            ->pluck('total', 'conversations.product_id');

        return view('SellerView.chat.index_seller', compact('products', 'selectedProduct', 'conversations', 'productChatCount', 'productUnreadCount'));
    }

    public function show(Conversation $conversation)
    {
        abort_unless($conversation->seller_id === \Illuminate\Support\Facades\Auth::id(), 403);

        Message::where('conversation_id', $conversation->id)
            ->where('sender_id', '!=', \Illuminate\Support\Facades\Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $conversation->load(['buyer', 'product']);
        $messages = $conversation->messages()->with('sender')->orderBy('created_at')->get();
        $chatPartner = $conversation->buyer;

        return view('SellerView.chat.show_seller', compact('messages', 'chatPartner', 'conversation'));
    }

    public function legacyShow($userId)
    {
        $currentUserId = \Illuminate\Support\Facades\Auth::id();

        $messages = Chat_seller::where(function ($query) use ($currentUserId, $userId) {
                $query->where(function ($q) use ($currentUserId, $userId) {
                    $q->where('sender_id', $currentUserId)->where('receiver_id', $userId);
                })->orWhere(function ($q) use ($currentUserId, $userId) {
                    $q->where('sender_id', $userId)->where('receiver_id', $currentUserId);
                });
            })
            ->orderBy('created_at')
            ->get();

        Chat_seller::where('receiver_id', $currentUserId)
            ->where('sender_id', $userId)
            ->update(['is_read' => true]);

        $chatPartner = User::findOrFail($userId);
        $conversation = null;

        return view('SellerView.chat.show_seller', compact('messages', 'chatPartner', 'conversation'));
    }

    public function store(Request $request, Conversation $conversation = null)
    {
        if (!$conversation && $request->filled('conversation_id')) {
            $conversation = Conversation::findOrFail($request->conversation_id);
        }

        if (!$conversation && $request->filled('receiver_id')) {
            $request->validate([
                'receiver_id' => 'required|exists:users,id',
                'message' => 'required|string|max:1000',
            ]);

            Chat_seller::create([
                'sender_id' => \Illuminate\Support\Facades\Auth::id(),
                'receiver_id' => $request->receiver_id,
                'message' => $request->message,
                'type' => 'text',
                'is_read' => false,
            ]);

            return back();
        }

        abort_unless($conversation && $conversation->seller_id === \Illuminate\Support\Facades\Auth::id(), 403);

        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => \Illuminate\Support\Facades\Auth::id(),
            'message' => $request->message,
        ]);

        $conversation->touch();

        return redirect()->route('seller.chat.show', $conversation);
    }
}
