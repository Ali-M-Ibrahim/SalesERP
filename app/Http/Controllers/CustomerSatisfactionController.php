<?php

namespace App\Http\Controllers;

use App\Models\CustomerSatisfactionAnswer;
use App\Models\CustomerSatisfactionInvitation;
use App\Models\CustomerSatisfactionQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerSatisfactionController extends Controller
{
    /**
     * Display the public satisfaction survey.
     */
    public function show(string $token)
    {
        $invitation = CustomerSatisfactionInvitation::with(['customer', 'visit.salesRep', 'visit.visitPurpose',])->where('token', $token)->firstOrFail();


        /*
         * Expired invitation.
         */
        if ($invitation->expires_at && $invitation->expires_at->isPast()) {
            return view('satisfaction.expired', compact('invitation'));
        }


        /*
         * Already completed.
         */
        if ($invitation->completed_at) {

            return view('satisfaction.completed', compact('invitation'));

        }


        /*
         * Record first opening.
         */
        if (!$invitation->opened_at) {

            $invitation->update(['opened_at' => now(),]);

        }


        $questions = CustomerSatisfactionQuestion::query()->where('is_active', true)->orderBy('sort_order')->orderBy('created_at')->get();


        return view('satisfaction.show', compact('invitation', 'questions'));
    }


    /**
     * Save customer survey response.
     */
    public function store(Request $request, string $token)
    {
        $invitation = CustomerSatisfactionInvitation::with(['customer', 'visit',])->where('token', $token)->firstOrFail();


        /*
         * Expired.
         */
        if ($invitation->expires_at && $invitation->expires_at->isPast()) {
            return redirect()->route('satisfaction.show', $token)->with('error', 'This feedback link has expired.');
        }


        /*
         * Already submitted.
         */
        if ($invitation->completed_at) {

            return redirect()->route('satisfaction.show', $token);

        }


        $questions = CustomerSatisfactionQuestion::query()->where('is_active', true)->orderBy('sort_order')->get();


        /*
         * Build validation dynamically
         * from configured questions.
         */
        $rules = [];


        foreach ($questions as $question) {

            $field = 'answers.' . $question->id;


            if ($question->type === 'rating') {

                $rules[$field] = [$question->is_required ? 'required' : 'nullable',

                    'integer', 'between:1,5',];

            } elseif (in_array($question->type, ['text', 'textarea',])) {

                $rules[$field] = [$question->is_required ? 'required' : 'nullable',

                    'string', 'max:3000',];

            }

        }


        $validated = $request->validate($rules);


        DB::transaction(function () use (
            $questions, $validated, $invitation
        ) {

            foreach ($questions as $question) {

                $value = $validated['answers'][$question->id] ?? null;


                CustomerSatisfactionAnswer::updateOrCreate(['invitation_id' => $invitation->id,

                        'question_id' => $question->id,], ['rating' => $question->type === 'rating' ? $value : null,

                        'answer' => in_array($question->type, ['text', 'textarea',]) ? $value : null,]);

            }


            $invitation->update(['completed_at' => now(),]);

        });


        return redirect()->route('satisfaction.show', $token)->with('success', 'Thank you for your feedback.');
    }
}
