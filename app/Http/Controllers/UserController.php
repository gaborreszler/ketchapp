<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
	/**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
    	$user = null;

		try {
			$user = User::findOrFail($id);
		} catch (ModelNotFoundException $e) {
			$model = lcfirst(substr(strrchr($e->getModel(), '\\'), 1));
			abort(404, sprintf("This %s does not exist", $model));
		}

		if ($id != Auth::id()) abort(403);

		return view('users.edit', ['user' => $user]);
    }
}
