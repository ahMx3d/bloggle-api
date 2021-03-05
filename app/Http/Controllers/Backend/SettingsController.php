<?php

namespace App\Http\Controllers\Backend;

use App\Models\Setting;
use Illuminate\Http\Request;
use Spatie\Valuestore\Valuestore;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    public function __construct() {
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
            'manage_settings,show_settings'
        )) return redirect_to('admin.index');

        $section     = (request()->filled('section'))? request()->section: 'general';
        $st_sections = Setting::select('section')->distinct()->pluck('section')->toArray();
        $settings    = Setting::whereSection($section)->get();

        return view('backend.settings.index', compact('section', 'st_sections', 'settings'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        for ($i=0; $i < count($request->id); $i++) {
            $input['value'] = $request->value[$i];
            Setting::whereId($request->id[$i])->first()->update($input);
        }
        $this->generate_cache();
        return redirect_with_msg(
            'admin.settings.index',
            'Settings Updated Successfully',
            'success'
        );
    }

    private function generate_cache()
    {
        $settings = Valuestore::make(config_path('settings.json'));
        Setting::all()->each(function ($item) use($settings){
            $settings->put($item->key, $item->value);
        });
    }
}
