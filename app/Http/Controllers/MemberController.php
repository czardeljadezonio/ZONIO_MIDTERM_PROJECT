<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Plan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB; // To use for more complex stats if needed
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Dompdf\Dompdf;
use Dompdf\Options;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource (Dashboard/Members Page).
     */
    public function index(Request $request)
        {
            // 1. Prepare the query (Start building the SQL)
            $query = Member::with('plan');
    
            // 2. Apply Search Filter
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('name', 'like', "%{$searchTerm}%")
                      ->orWhere('email', 'like', "%{$searchTerm}%");
                });
            }
    
            // 3. Apply Plan Filter
            if ($request->filled('plan_id') && $request->plan_id != '') {
                $query->where('plan_id', $request->plan_id);
            }
    
            // 4. Fetch real data
            $plans = Plan::all(); // Used for dropdowns
    
            // --- FIX IS HERE ---
            // We use '$query' (which has the filters applied). 
            // I deleted the second '$members = Member::with...' block that was below this.
            $members = $query->orderBy('name')->get()->map(function ($member) {
                return (object) [
                    'id' => $member->id,
                    'name' => $member->name,
                    'email' => $member->email,
                    'start_date' => $member->start_date,
                    'plan_id' => $member->plan_id,
                    'plan_name' => $member->plan->name ?? null,
                    'photo' => $member->photo,
                ];
            });
    
            // 5. Calculate statistics
            // Note: These counts track the TOTAL database numbers, not just the filtered results.
            // If you want these stats to reflect the search results, use $query->count() instead of Member::count().
            $totalMembers = Member::count();
            $totalPlans = Plan::count();
            
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
            // 1. Validation
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:members,email',
                'start_date' => 'required|date',
                'plan_id' => 'nullable|exists:plans,id',
                'photo' =>  'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);
    
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }
    
            // 2. Prepare the data
            // We grab all the text inputs first
            $data = $request->except('photo'); 
    
            // 3. Handle the File Upload manually
            if ($request->hasFile('photo')) {
                // This saves the file to storage/app/public/member-photos
                $path = $request->file('photo')->store('member-photos', 'public');
                
                // This adds the PATH STRING to the data array so it gets saved in the DB
                $data['photo'] = $path;
            }
    
            // 4. Create the Member using our modified $data array
            Member::create($data);
    
            return back()->with('success', 'Member added successfully!');
        }
        
        /**
         * Update the specified resource in storage.
     */
    public function update(Request $request, Member $member)
        {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:members,email,'.$member->id,
                'start_date' => 'required|date',
                'plan_id' => 'nullable|exists:plans,id',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $data = $request->all();

            if ($request->hasFile('photo')) {
                // 1. Delete old photo if it exists
                if ($member->photo) {
                    Storage::disk('public')->delete($member->photo);
                }
                // 2. Store new photo
                $photoPath = $request->file('photo')->store('member-photos', 'public');
                $data['photo'] = $photoPath;
            }

            $member->update($data);

            return back()->with('success', 'Member updated successfully!');
        }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {
        // 1. Database Delete Logic
        $member->delete();
        return back()->with('success', 'Member successfully move to trash.');
    }

    public function trash()
    {
        $members = Member::onlyTrashed()->with('plan')->latest('deleted_at')->get();
        $plans = Plan::all();

        return view('trash', compact('members', 'plans'));
    }

    public function restore($id)
    {
        $member = Member::withTrashed()->findOrfail($id);
        $member->restore();

        return redirect()->route('members.trash')->with('success', 'Member restored successfully. ');
    }

    public function forceDelete($id)
    {
        $member = Member::withTrashed()->findOrFail($id);

        if ($member->photo) {
            Storage::disk('public')->delete($member->photo);
        }
        
        $member->forceDelete();

        return redirect()->route('members.trash')->with('success', 'Member Permanently deleted');

    }

    public function export(Request $request)
    {
        // 1. Prepare Query (Same logic as Index to respect filters)
        $query = Member::with('plan');

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        if ($request->filled('plan_id') && $request->plan_id != '') {
            $query->where('plan_id', $request->plan_id);
        }

        // 2. Fetch Data
        $members = $query->orderBy('name')->get();

        $filename = 'members_export_' . date('Y-m-d_His') . '.pdf';

        // 3. Generate HTML
        $html = '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Members Export</title>
            <style>
                body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
                .header { text-align: center; margin-bottom: 20px; }
                .header h1 { margin: 0; color: #2563eb; } /* Blue-600 */
                .meta { text-align: center; color: #666; font-size: 11px; margin-bottom: 20px; }
                
                table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                th { 
                    background-color: #2563eb; 
                    color: white; 
                    padding: 8px; 
                    text-align: left; 
                    font-size: 11px;
                }
                td { 
                    padding: 8px; 
                    border-bottom: 1px solid #ddd; 
                    font-size: 11px;
                }
                tr:nth-child(even) { background-color: #f9fafb; }
                
                .badge { 
                    padding: 2px 6px; 
                    background-color: #dbeafe; 
                    color: #1e40af; 
                    border-radius: 4px; 
                    font-size: 10px; 
                    display: inline-block;
                }
                .footer {
                    margin-top: 30px;
                    text-align: right;
                    font-size: 10px;
                    color: #999;
                    border-top: 1px solid #eee;
                    padding-top: 10px;
                }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>Members Report</h1>
            </div>
            
            <div class="meta">
                Generated on: ' . date('F d, Y \a\t h:i A') . ' <br>
                Total Records: ' . $members->count() . '
            </div>

            <table>
                <thead>
                    <tr>
                        <th style="width: 5%">#</th>
                        <th style="width: 25%">Name</th>
                        <th style="width: 30%">Email</th>
                        <th style="width: 20%">Plan</th>
                        <th style="width: 20%">Start Date</th>
                    </tr>
                </thead>
                <tbody>';

        $count = 1;
        foreach ($members as $member) {
            $planName = $member->plan ? $member->plan->name : 'No Plan';
            // Simple logic: Highlight "No Plan" differently if you want
            $planBadge = $member->plan 
                ? '<span class="badge">' . htmlspecialchars($planName) . '</span>' 
                : '<span style="color: #999;">-</span>';

            $html .= '<tr>
                    <td>' . $count++ . '</td>
                    <td><strong>' . htmlspecialchars($member->name) . '</strong></td>
                    <td>' . htmlspecialchars($member->email) . '</td>
                    <td>' . $planBadge . '</td>
                    <td>' . \Carbon\Carbon::parse($member->start_date)->format('M d, Y') . '</td>
                </tr>';
        }

        $html .= '</tbody>
            </table>

            <div class="footer">
                Page 1 of 1
            </div>
        </body>
        </html>';

        // 4. Render PDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true); // Important for images/fonts if needed

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->stream($filename, ['Attachment' => true]);
    }

}
