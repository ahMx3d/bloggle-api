<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class Contact extends Model
{
    use SearchableTrait;
    //
    protected $guarded = [];
    protected $searchable = [
        /**
         * Columns and their priority in search results.
         * Columns with higher values are more important.
         * Columns with equal values have equal importance.
         *
         * @var array
         */
        'columns' => [
            'contacts.name'    => 10,
            'contacts.email'   => 10,
            'contacts.mobile'  => 10,
            'contacts.title'   => 10,
            'contacts.message' => 10

        ]
    ];

    /**
     * Display the status record for blade views.
     */
    public function status()
    {
        return ($this->status)? 'Seen': 'New';
    }
}
