<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Submission;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    public function suggest($id)
    {
        $member = Member::findOrFail($id);
        return view('public.submission', compact('member'));
    }

    public function storeSuggestion(Request $request, $id)
    {
        $member = Member::findOrFail($id);

        $request->validate([
            'submission_type' => 'required|in:add,update',
            'usulan' => 'required|string|max:1000',
            'reason' => 'nullable|string|max:1000',
            'submitter_name' => 'nullable|string|max:255',
            'submitter_email' => 'nullable|email|max:255',
            'submitter_phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('submissions', 'public');
        }

        Submission::create([
            'target_member_id' => $member->id,
            'submission_type' => $request->submission_type,
            'submitted_data' => json_encode(['usulan' => $request->usulan]),
            'reason' => $request->reason,
            'submitter_name' => $request->submitter_name,
            'submitter_email' => $request->submitter_email,
            'submitter_phone' => $request->submitter_phone,
            'photo_path' => $photoPath,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Terima kasih, perubahan Anda sedang ditinjau koordinator keluarga. Proses ini biasanya memakan waktu 1-3 hari kerja.');
    }
}
