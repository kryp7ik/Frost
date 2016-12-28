<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Messenger\EloquentMessengerRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessengerController extends Controller
{

    protected $messengerRepo;

    public function __construct(EloquentMessengerRepository $eloquentMessengerRepository)
    {
        $this->messengerRepo = $eloquentMessengerRepository;
    }

    /**
     * Expects a POST|GET request containing a 'id' attribute that corresponds to a Conversation
     * Returns a Conversation with all messages or 404 on fail
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getConversation(Request $request)
    {
        if ($request->get('id')) {
            $conversation = $this->messengerRepo->findConversationById($request->get('id'));
            $this->messengerRepo->userReadConversation(Auth::user(), $conversation);
            return response()->json($conversation);
        } else {
            return response()->json(['error' => 'The Conversation id was not supplied in the request.'], 404);
        }
    }

    /**
     * Expects a POST|GET request containing the following attributes
     * 'content' = The message content
     * 'conversation' = The id of the conversation
     * Returns the Message object on success or 404 on fail
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMessage(Request $request)
    {
        if ($request->get('content') && $request->get('conversation')) {
            $content = $request->get('content');
            if ($conversation = $this->messengerRepo->findConversationById($request->get('conversation'))) {
                $message = $this->messengerRepo->createMessage(Auth::user(), $conversation, $content);
                return response()->json($message);
            }
        }
        return response()->json(['error' => 'A conversation could not be found!'], 404);
    }

}
