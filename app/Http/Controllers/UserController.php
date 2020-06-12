<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
	/**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
    	$user = null;

    	try {
			$user = User::findOrFail($id);
		} catch (ModelNotFoundException $e) {
    		$model = lcfirst(substr(strrchr($e->getModel(), '\\'), 1));
    		abort(404, sprintf("This %s does not exist", $model));
		}

		return view('users.show', ['user' => $user]);
    }

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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
    	$user = null;

		try {
			$user = User::findOrFail($id);
		} catch (ModelNotFoundException $e) {
			$model = lcfirst(substr(strrchr($e->getModel(), '\\'), 1));
			abort(404, sprintf("This %s does not exist", $model));
		}

		if ($id != Auth::id()) abort(403);

		$validator = Validator::make($request->input(), [
			/*'timezone' => 'required|timezone',*/
			'reminded_daily' => 'required|boolean',
			'reminded_weekly' => 'required|boolean',
			'reminded_monthly' => 'required|boolean',
		]);

		if ($validator->fails())
			return redirect()->route('users.edit', ['user' => Auth::id()])->withErrors($validator);

		$user->update($request->input());

		return redirect()->route('users.show', ['user' => Auth::id()])->with('success', 'Changes have been saved.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
		$user = null;

		try {
			$user = User::findOrFail($id);
		} catch (ModelNotFoundException $e) {
			$model = lcfirst(substr(strrchr($e->getModel(), '\\'), 1));
			abort(404, __(sprintf("This %s does not exist", $model)));
		}

		if ($id != Auth::id()) abort(403);

		$user->delete();//todo:consider soft deleting

		return redirect()->route('index')->with('success', 'Account has been deleted.');
    }
}
