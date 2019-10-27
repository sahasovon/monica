<?php

namespace App\Services\Contact\Relationship;

use App\Models\Contact\Contact;
use App\Models\Relationship\Relationship;
use App\Services\BaseService;

class DestroyRelationship extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'account_id' => 'required|integer|exists:accounts,id',
            'relationship_id' => 'required|integer|exists:relationships,id',
        ];
    }

    /**
     * Destroy a relationship.
     *
     * @param array $data
     * @return bool
     */
    public function execute(array $data) : bool
    {
        $this->validate($data);

        $relationship = Relationship::where('account_id', $data['account_id'])
                                    ->findOrFail($data['relationship_id']);

        $otherContact = $relationship->ofContact;

        $this->deleteRelationship($relationship);

        $this->deletePartialContact($otherContact);

        return true;
    }

    /**
     * Delete relationship.
     *
     * @param Relationship $relationship
     */
    private function deleteRelationship(Relationship $relationship)
    {
        $reverseRelationship = $relationship->reverseRelationship();
        if ($reverseRelationship) {
            $reverseRelationship->delete();
        }

        $relationship->delete();
    }

    /**
     * Delete partial contact.
     *
     * @param Contact $contact
     */
    private function deletePartialContact(Contact $contact)
    {
        // the contact is partial - if the relationship is deleted, the partial
        // contact has no reason to exist anymore
        if ($contact->is_partial) {
            $contact->deleteEverything();
        }
    }
}
