<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plan;
use Illuminate\Support\Facades\Validator;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource (Plans Management Page).
     */
    public function index()
    {
        // Fetch real data, counting the number of members associated with each plan (required feature)
        $plans = Plan::withCount('members')->orderBy('name')->get();

        return view('plans', compact('plans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validation Logic
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:plans,name',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // 2. Database Save Logic
        Plan::create($request->all());

        return back()->with('success', 'Plan created successfully!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Plan $plan)
    {
        // 1. Validation Logic
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:plans,name,'.$plan->id, // Unique check excluding current ID
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // 2. Database Update Logic
        $plan->update($request->all());

        return back()->with('success', 'Plan updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plan $plan)
    {
        // 1. Database Delete Logic
        $plan->delete();

        // This deletion automatically sets the `plan_id` of associated members to NULL due to the
        // `onDelete('set null')` foreign key constraint in the migration.

        return back()->with('success', 'Plan deleted successfully!');
    }
}
