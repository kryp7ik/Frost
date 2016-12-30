<?php

namespace App\Repositories\Messenger;

use App\Events\MessageSent;
use App\Models\Auth\User;
use App\Models\Messenger\Conversation;
use App\Models\Messenger\Message;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EloquentMessengerRepository
{

    /**
     * Accepts an array of User IDs to be attached to the new Conversation
     *
     * @param array $users
     * @return Conversation
     */
    public function createConversation($users)
    {
        $conversation = new Conversation();
        $conversation->save();
        foreach ($users as $user_id) {
            $this->addUserToConversation($user_id, $conversation);
        }
        return $conversation;
    }

    /**
     * Adds a User to a Conversation
     *
     * @param int $user_id
     * @param Conversation $conversation
     */
    public function addUserToConversation($user_id, Conversation $conversation)
    {
        $conversation->users()->attach($user_id, ['lastread' => new Carbon()]);
    }

    /**
     * Retrieves an array of the other users along with the associated conversation between them and the logged in user
     *
     * @return array
     */
    public function getUserList()
    {
        $loggedUser = Auth::user();
        $userList = [];
        // May have to use pluck('id', 'name') instead of select
        $users = User::with('conversations')
            ->where('id', '!=', $loggedUser->id)
            ->get();
        foreach ($users as $user) {
            $conversation = $this->findConversationWithUsers($loggedUser, $user);
            $lastread = DB::table('conversation_user')
                ->select('lastread')
                ->where('user_id', $loggedUser->id)
                ->where('conversation_id', $conversation->id)
                ->get();
            $userList[] = [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'online' => $user->isOnline()
                ],
                'conversation' => [
                    'id' => $conversation->id,
                    'new' => ($conversation->updated_at != $lastread) ? true : false,
                ]
            ];
        }
        return $userList;
    }

    /**
     * Accepts an array of User IDs and searches to see if a conversation already exists with those users
     * If a Conversation does not exist it creates a new one
     *
     * @param User $user_a
     * @param User $user_b
     * @return Conversation
     *
     */
    public function findConversationWithUsers($user_a, $user_b)
    {
        $collection = $user_a->conversations->intersect($user_b->conversations);
        if($collection->isEmpty()) {
            $conversation = $this->createConversation([$user_a->id, $user_b->id]);
            return $conversation;
        } else {
            return $collection->first();
        }
    }

    /**
     * Find a Conversation by id.  Returns the Conversation or false on fail
     *
     * @param int $id
     * @return bool|Conversation
     */
    public function findConversationById($id)
    {
        $conversation = Conversation::where('id', $id)->first();
        return ($conversation) ? $conversation : false;
    }

    /**
     * Creates a new Message in a Conversation
     * Fires MessageSent event which broadcasts the message using Socket.io and Redis
     *
     * @param User $user
     * @param Conversation $conversation
     * @param string $content
     * @return Message
     */
    public function createMessage(User $user, Conversation $conversation, $content)
    {
        $message = new Message([
            'user_id' => $user->id,
            'conversation_id' => $conversation->id,
            'content' => $content
        ]);
        $message->save();
        event(new MessageSent($message));
        return $message;
    }

    /**
     * Touches the pivot column 'lastread' which is used to determine new messages
     *
     * @param User $user
     * @param Conversation $conversation
     */
    public function userReadConversation(User $user, Conversation $conversation)
    {
        $conversation->users()->updateExistingPivot($user->id, ['lastread' => new Carbon()]);
    }

    /**
     * Retrieves an array of conversations containing unread messages
     *
     * @param User $user
     * @return array
     */
    public function getUnreadConversations(User $user)
    {
        $unread = [];
        foreach ($user->conversations as $conversation) {
            if ($conversation->pivot->lastread != $conversation->updated_at) {
                $unread[] = $conversation;
            }
        }
        return $unread;
    }

}