<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    public function index(Request $request)
    {
        $query = Submission::with(['targetMember', 'reviewer'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $submissions = $query->paginate(15);
        
        return view('admin.submissions.index', compact('submissions'));
    }

    public function show(Submission $submission)
    {
        $submission->load(['targetMember', 'reviewer']);
        return view('admin.submissions.show', compact('submission'));
    }

    public function updateStatus(Request $request, Submission $submission)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $submission->update([
            'status' => $request->status,
            'reviewed_by' => Auth::id()
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'updated submission status to ' . $request->status,
            'target_type' => 'submission',
            'target_id' => $submission->id
        ]);

        return redirect()->route('admin.submissions.show', $submission->id)->with('success', 'Status pengajuan berhasil diperbarui menjadi ' . ucfirst($request->status));
    }

    public function destroy(Submission $submission)
    {
        if ($submission->photo_path && Storage::disk('public')->exists($submission->photo_path)) {
            Storage::disk('public')->delete($submission->photo_path);
        }
        
        $submissionId = $submission->id;
        $submission->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'deleted submission',
            'target_type' => 'submission',
            'target_id' => $submissionId
        ]);

        return redirect()->route('admin.submissions.index')->with('success', 'Pengajuan berhasil dihapus.');
    }
}
