<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = Member::query();

        if ($request->filled('q')) {
            $query->where('full_name', 'like', '%' . $request->q . '%')
                  ->orWhere('nickname', 'like', '%' . $request->q . '%');
        }

        $members = $query->with(['father', 'mother'])->orderBy('id', 'desc')->paginate(15);

        return view('admin.members.index', compact('members'));
    }

    public function create()
    {
        $allMembers = Member::orderBy('full_name')->get();
        return view('admin.members.create', compact('allMembers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'nickname' => 'nullable|string|max:100',
            'gender' => 'required|in:male,female',
            'birth_date' => 'nullable|date',
            'birth_place' => 'nullable|string|max:100',
            'current_address' => 'nullable|string',
            'occupation' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'father_id' => 'nullable|exists:members,id',
            'mother_id' => 'nullable|exists:members,id',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('members', 'public');
        }

        // Calculate generation
        $generation = 1;
        if ($request->father_id) {
            $father = Member::find($request->father_id);
            if ($father) $generation = max($generation, $father->generation + 1);
        }
        if ($request->mother_id) {
            $mother = Member::find($request->mother_id);
            if ($mother) $generation = max($generation, $mother->generation + 1);
        }

        $member = Member::create([
            'full_name' => $request->full_name,
            'nickname' => $request->nickname,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
            'birth_place' => $request->birth_place,
            'current_address' => $request->current_address,
            'occupation' => $request->occupation,
            'phone_number' => $request->phone_number,
            'photo_path' => $photoPath,
            'father_id' => $request->father_id,
            'mother_id' => $request->mother_id,
            'generation' => $generation,
            'is_active' => true,
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'added member',
            'target_type' => 'member',
            'target_id' => $member->id
        ]);

        return redirect()->route('admin.members.index')->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function edit(Member $member)
    {
        $allMembers = Member::where('id', '!=', $member->id)->orderBy('full_name')->get();
        return view('admin.members.edit', compact('member', 'allMembers'));
    }

    public function update(Request $request, Member $member)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'nickname' => 'nullable|string|max:100',
            'gender' => 'required|in:male,female',
            'birth_date' => 'nullable|date',
            'birth_place' => 'nullable|string|max:100',
            'current_address' => 'nullable|string',
            'occupation' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'father_id' => 'nullable|exists:members,id',
            'mother_id' => 'nullable|exists:members,id',
        ]);

        $photoPath = $member->photo_path;
        if ($request->hasFile('photo')) {
            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = $request->file('photo')->store('members', 'public');
        }

        $generation = 1;
        if ($request->father_id) {
            $father = Member::find($request->father_id);
            if ($father) $generation = max($generation, $father->generation + 1);
        }
        if ($request->mother_id) {
            $mother = Member::find($request->mother_id);
            if ($mother) $generation = max($generation, $mother->generation + 1);
        }

        $member->update([
            'full_name' => $request->full_name,
            'nickname' => $request->nickname,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
            'birth_place' => $request->birth_place,
            'current_address' => $request->current_address,
            'occupation' => $request->occupation,
            'phone_number' => $request->phone_number,
            'photo_path' => $photoPath,
            'father_id' => $request->father_id,
            'mother_id' => $request->mother_id,
            'generation' => $generation,
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'updated member',
            'target_type' => 'member',
            'target_id' => $member->id
        ]);

        return redirect()->route('admin.members.index')->with('success', 'Anggota berhasil diperbarui.');
    }

    public function destroy(Member $member)
    {
        if ($member->photo_path && Storage::disk('public')->exists($member->photo_path)) {
            Storage::disk('public')->delete($member->photo_path);
        }
        
        $memberId = $member->id;
        $member->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'deleted member',
            'target_type' => 'member',
            'target_id' => $memberId
        ]);

        return redirect()->route('admin.members.index')->with('success', 'Anggota berhasil dihapus.');
    }
}
