<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Contact;

class ContactsController extends Controller
{
    private $pagination_count;  // Global pagination count constant.

    /**
     * Construct pagination count constant.
     *
     * @return void
     */
    public function __construct() {
        $this->pagination_count = config(
            'constants.ADMIN_PAGINATION_COUNT'
        );

        // Bug here
        if(auth()->check()){$this->middleware('auth');}
        else{return view('backend.auth.login');}
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->ability(
            'admin',
            'manage_contact_us,show_contact_us'
        )) return redirect_to('admin.index');

        $keyword  = (request()->filled('keyword'))? request()->keyword: null;
        $status   = (request()->filled('status'))? request()->status: null;
        $sort_by  = (request()->filled('sort_by'))? request()->sort_by: 'id';
        $order_by = (request()->filled('order_by'))? request()->order_by: 'desc';
        $limit_by = (request()->filled('limit_by'))? request()->limit_by: $this->pagination_count;

        $messages = Contact::query();
        $messages = ($keyword)? $messages->search($keyword): $messages;
        $messages = ($status != null)? $messages->whereStatus($status): $messages;
        $messages = $messages->orderBy(
            $sort_by,
            $order_by
        )->paginate($limit_by);

        return view('backend.contacts.index', compact('messages'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!auth()->user()->ability(
            'admin',
            'display_contact_us'
        )) return redirect_to('admin.index');

        $message = Contact::whereId($id)->first();
        if($message and !$message->status){
            $message->status = 1;
            $message->save();
        }
        return view('backend.contacts.show', compact('message'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!auth()->user()->ability(
            'admin',
            'delete_contact_us'
        )) return redirect_to('admin.index');

        try {
            $message = Contact::whereId($id)->first();
            if(!$message) return redirect_with_msg(
                'admin.contact_us.index',
                'Oops, Something went wrong.',
                'danger'
            );
            $message->delete();

            return redirect_with_msg(
                'admin.contact_us.index',
                'Message deleted successfully',
                'success'
            );
        } catch (\Exception $e) {
            return redirect_with_msg(
                'admin.contact_us.index',
                'Oops, Something went wrong.',
                'danger'
            );
        }
    }
}
