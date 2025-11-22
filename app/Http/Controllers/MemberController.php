<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Plan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB; // To use for more complex stats if needed

class MemberController extends Controller
{
    /**
     * Display a listing of the resource (Dashboard/Members Page).
     */
    public function index()
    {
        // Fetch real data from the database
        $plans = Plan::all(); // Used for dropdowns

        // Fetch members along with their associated plan name (using Eager Loading)
        $members = Member::with('plan')->orderBy('name')->get()->map(function ($member) {
            // Map the Eloquent object to an object structure that resembles the old mock data for front-end compatibility
            return (object) [
                'id' => $member->id,
                'name' => $member->name,
                'email' => $member->email,
                'start_date' => $member->start_date,
                'plan_id' => $member->plan_id,
                // Get the plan name using the eager-loaded relationship
                'plan_name' => $member->plan->name ?? null,
            ];
        });

        // Calculate real statistics for the cards
        $totalMembers = Member::count();
        $totalPlans = Plan::count();
        // Calculate the membership rate (percentage of members actively enrolled in a plan)
        $membersWithPlan = Member::whereNotNull('plan_id')->count();
        $membershipRate = $totalMembers > 0 ? round(($membersWithPlan / $totalMembers) * 100) . '%' : '0%';

        $stats = [
            'total_members' => $totalMembers,
            'total_plans' => $totalPlans,
            'membership_rate' => $membershipRate,
        ];

        return view('dashboard', compact('members', 'plans', 'stats'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validation Logic
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email',
            'start_date' => 'required|date',
            'plan_id' => 'nullable|exists:plans,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // 2. Database Save Logic
        Member::create($request->all());

        return back()->with('success', 'Member added successfully!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Member $member)
    {
        // 1. Validation Logic
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email,'.$member->id, // Unique check excluding current ID
            'start_date' => 'required|date',
            'plan_id' => 'nullable|exists:plans,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // 2. Database Update Logic
        $member->update($request->all());

        return back()->with('success', 'Member updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {
        // 1. Database Delete Logic
        $member->delete();

        return back()->with('success', 'Member deleted successfully!');
    }
}
