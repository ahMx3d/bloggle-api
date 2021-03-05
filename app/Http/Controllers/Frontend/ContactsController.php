<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\ContactRequest;
use App\Interfaces\Frontend\Repositories\IContactRepository;

class ContactsController extends Controller
{
    private $contact_repo;     // Contacts Repository

    /**
     * Construct contacts repository interface.
     *
     * @param IContactRepository $contact_repo
     * @return void
     */
    public function __construct(IContactRepository $contact_repo)
    {
        $this->contact_repo = $contact_repo;
    }

    /**
     * Store contact messages.
     *
     * @param Illuminate\Http\ContactRequest $request
     * @param string $post_slug
     * @return Illuminate\Http\Response
     */
    public function store(ContactRequest $request)
    {
        try {
            $this->contact_repo->contact_store($request);
            return redirect_with_msg(
                'frontend.index',
                'Message sent successfully',
                'success'
            );
        } catch (\Throwable $th) {
            return redirect_with_msg(
                'frontend.index',
                'Oops Something Went wrong',
                'danger'
            );
        }
    }
}
