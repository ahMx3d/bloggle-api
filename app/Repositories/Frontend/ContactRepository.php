<?php

namespace App\Repositories\Frontend;

use App\Models\Contact;
use App\Interfaces\Frontend\Repositories\IContactRepository;

class ContactRepository implements IContactRepository
{
    private $contact_model;    // Repository model.
    /**
     * Construct contacts model
     *
     * @return void
     */
    public function __construct()
    {
        $this->contact_model = Contact::class;
    }

    /**
     * Create new contact message.
     *
     * @param object $request
     * @return void
     */
    public function contact_store($request)
    {
        $data =[
            'name'    => $request->name,
            'email'   => $request->email,
            'mobile'  => $request->mobile,
            'title'   => $request->title,
            'message' => $request->message
        ];
        $this->contact_model::create($data);
    }
}
