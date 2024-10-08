<?php

namespace Modules\Users\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Users\App\Models\User;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
          $trashed=!empty(request('trashed'))?true:false;

        // $data=User::withTrashed($trashed)->paginate(5);
        $data=User::get();//->paginate(5);
        return view('users::index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
        $data=$request->validated();
 $data= User::create($data);
       $data->replicate()->save();
       return redirect('users::index') ;
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
         $data= User::withTrashed()->findOrFail($id);
        return view('users::show',compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data=User::find($id);
        return view('users::edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
          $test=User::find($id);
        // $this->authorize('update',$test);
        $data=$request->validated();

        $test ->update($data);
       return redirect('users::index') ;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $data=User::find($id);
        $data->delete();
    }
}
