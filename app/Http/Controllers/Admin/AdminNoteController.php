<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNote;
use App\Models\Customer;
use App\Models\Visit;
use Illuminate\Http\Request;

class AdminNoteController extends Controller
{
    /*
     * =========================================================
     * CUSTOMER NOTE
     * =========================================================
     */
    public function storeCustomer(Request $request, Customer $customer)
    {
        $validated = $request->validate(['note' => ['required', 'string', 'max:5000',],

            'is_important' => ['nullable', 'boolean',],]);


        /*
         * Get customer's currently assigned sales rep.
         */
        $salesRepId = $customer->currentAssignment()->value('sales_rep_id');


        $customer->adminNotes()->create(['created_by' => auth()->id(),

            'sales_rep_id' => $salesRepId,

            'note' => $validated['note'],

            'is_important' => $request->boolean('is_important'),]);


        return back()->with('success', 'Note added successfully.');
    }


    /*
     * =========================================================
     * VISIT NOTE
     * =========================================================
     */
    public function storeVisit(Request $request, Visit $visit)
    {
        $validated = $request->validate(['note' => ['required', 'string', 'max:5000',],

            'is_important' => ['nullable', 'boolean',],]);


        /*
         * Visit already belongs to a specific sales rep,
         * so use the visit salesperson instead of the
         * customer's current assignment.
         */
        $visit->adminNotes()->create(['created_by' => auth()->id(),

            'sales_rep_id' => $visit->sales_rep_id,

            'note' => $validated['note'],

            'is_important' => $request->boolean('is_important'),]);


        return response()->json(['success' => true, 'message' => 'Note added successfully.',]);
    }


    /*
     * =========================================================
     * DELETE NOTE
     * =========================================================
     */
    public function destroy(AdminNote $adminNote)
    {
        /*
         * Optional rule:
         * only the person who created the note can delete it.
         */
        abort_unless($adminNote->created_by === auth()->id(), 403);


        $adminNote->delete();


        return back()->with('success', 'Note deleted successfully.');
    }
}
