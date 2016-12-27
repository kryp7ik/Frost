<?php

namespace App\Repositories\Messenger;

use App\Models\Auth\User;
use App\Models\Messenger\Conversation;
use App\Models\Messenger\Message;
use Carbon\Carbon;

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
     * Accepts an array of User IDs and searches to see if a conversation already exists with those users
     * If a Conversation does not exist it creates a new one
     *
     * @param array $users
     * @return Conversation
     *
     * $users = User::whereHas('posts', function($q){
    $q->where('created_at', '>=', '2015-01-01 00:00:00');
    })->get();
     */
    public function findConversationWithUsers($users)
    {
        $conversation = Conversation::with(['messages',
            'users' => function ($q) use ($users) {
                $q->wherePivotIn('user_id', $users);
            }
        ])->first();
        if(!$conversation) {
            $conversation = $this->createConversation($users);
        }
        return $conversation;
    }

    /**
     * Creates a new Message in a Conversation
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


}