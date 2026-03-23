<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;

class PublicController extends Controller
{
    public function index()
    {
        // Get random members for running text
        $randomMembers = Member::inRandomOrder()->take(5)->get();
        return view('public.index', compact('randomMembers'));
    }

    public function search(Request $request)
    {
        $q = $request->input('q');
        
        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $members = Member::where('full_name', 'like', "%{$q}%")
            ->orWhere('nickname', 'like', "%{$q}%")
            ->with(['father', 'mother'])
            ->take(10)
            ->get();

        $results = $members->map(function ($member) {
            // Konteks
            if ($member->father && $member->mother) {
                $context = "Anak dari {$member->father->full_name} & {$member->mother->full_name}";
            } elseif ($member->father) {
                $context = "Anak dari {$member->father->full_name}";
            } elseif ($member->mother) {
                $context = "Anak dari {$member->mother->full_name}";
            } else {
                $context = "Generasi ke-{$member->generation}";
            }

            // Inisial avatar (1-2 huruf pertama)
            $initials = collect(explode(' ', $member->full_name))
                ->map(function ($word) { return strtoupper(substr($word, 0, 1)); })
                ->take(2)
                ->join('');

            return [
                'id' => $member->id,
                'full_name' => $member->full_name,
                'nickname' => $member->nickname,
                'photo_path' => $member->photo_path ? asset('storage/' . $member->photo_path) : null,
                'initials' => $initials,
                'context' => $context
            ];
        });

        return response()->json($results);
    }

    public function profile($id)
    {
        $member = Member::with([
            'father', 
            'mother', 
            'children', 
            'marriagesAsHusband.wife', 
            'marriagesAsWife.husband'
        ])->findOrFail($id);

        return view('public.profile', compact('member'));
    }
}
