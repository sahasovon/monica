<?php

namespace Tests\Unit\Services\Contact\Conversation;

use App\Models\Account\Account;
use App\Models\Contact\Contact;
use App\Models\Contact\Conversation;
use App\Models\Contact\Message;
use App\Services\Contact\Conversation\UpdateMessage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class UpdateMessageTest extends TestCase
{
    use DatabaseTransactions;

    public function test_it_updates_a_conversation()
    {
        $conversation = factory(Conversation::class)->create([]);

        $message = factory(Message::class)->create([
            'conversation_id' => $conversation->id,
            'account_id' => $conversation->account->id,
            'contact_id' => $conversation->contact->id,
            'content' => 'tititi',
            'written_at' => '2009-01-01',
            'written_by_me' => false,
        ]);

        $request = [
            'account_id' => $conversation->account->id,
            'contact_id' => $conversation->contact->id,
            'conversation_id' => $conversation->id,
            'message_id' => $message->id,
            'written_at' => now(),
            'written_by_me' => true,
            'content' => 'lorem',
        ];

        $message = app(UpdateMessage::class)->execute($request);

        $this->assertDatabaseHas('messages', [
            'id' => $message->id,
            'account_id' => $conversation->account->id,
            'contact_id' => $conversation->contact->id,
            'conversation_id' => $conversation->id,
            'written_by_me' => true,
            'content' => 'lorem',
        ]);

        $this->assertInstanceOf(
            Message::class,
            $message
        );
    }

    public function test_it_fails_if_wrong_parameters_are_given()
    {
        $request = [
            'account_id' => 1,
            'conversation_id' => 2,
            'message_id' => 3,
            'written_at' => now(),
            'written_by_me' => true,
            'content' => 'lorem',
        ];

        $this->expectException(ValidationException::class);

        app(UpdateMessage::class)->execute($request);
    }

    public function test_it_throws_an_exception_if_message_does_not_exist()
    {
        $account = factory(Account::class)->create();
        $contact = factory(Contact::class)->create([
            'account_id' => $account->id,
        ]);
        $conversation = factory(Conversation::class)->create([
            'account_id' => $account->id,
            'contact_id' => $contact->id,
        ]);
        $message = factory(Message::class)->create([]);

        $request = [
            'account_id' => $account->id,
            'contact_id' => $contact->id,
            'conversation_id' => $conversation->id,
            'message_id' => $message->id,
            'written_at' => now(),
            'written_by_me' => true,
            'content' => 'lorem',
        ];

        $this->expectException(ModelNotFoundException::class);

        app(UpdateMessage::class)->execute($request);
    }
}
