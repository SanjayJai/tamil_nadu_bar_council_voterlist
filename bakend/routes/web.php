<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Advocate;

Route::get('/', function () {
    return json_encode([
        'message' => 'Welcome to the backend API',
    ]);
});



// Single handler closure reused for both web and API route prefixes.
// $advocateSearchHandler = function (Request $request) {
//     $data = $request->validate([
//         'mode' => 'required|in:name,enrollment',
//         'query' => 'required|string|max:255',
//     ]);

//     $mode = $data['mode'];
//     $query = trim($data['query']);

//     // Suggestion mode for name autocomplete
//     if ($mode === 'name' && $request->boolean('suggest')) {
//         $suggestions = Advocate::where('name', 'like', $query . '%')
//             ->orderBy('name')
//             ->limit(10)
//             ->get([
//                 'id', 'name', 'enrollment_no_str', 'enrollment_no', 'year',
//                 'father_name', 'district', 'bar_association', 'gender',
//             ]);

//         return response()->json(['success' => true, 'suggestions' => $suggestions], 200);
//     }

//     $adv = null;

//     if ($mode === 'name') {
//         // return the first match for the full search
//         $adv = Advocate::where('name', 'like', $query . '%')->first();
//     } else {
//         // enrollment mode: support formats like "265(a)/119" or "265/119" or just "265(a)"
//         if (str_contains($query, '/')) {
//             [$enoPart, $yearPart] = array_map('trim', explode('/', $query, 2));
//             $yearDigits = preg_replace('/[^0-9]/', '', $yearPart);

//             if ($enoPart !== '') {
//                 $adv = Advocate::where('enrollment_no_str', $enoPart)
//                     ->when($yearDigits !== '', fn($q) => $q->where('year', $yearDigits))
//                     ->first();
//             }

//             if (! $adv) {
//                 $digits = preg_replace('/[^0-9]/', '', $enoPart);
//                 if ($digits !== '') {
//                     $adv = Advocate::where('enrollment_no', $digits)
//                         ->when($yearDigits !== '', fn($q) => $q->where('year', $yearDigits))
//                         ->first();
//                 }
//             }
//         }

//         // fallback: try exact enrollment_no_str or numeric only
//         if (! $adv) {
//             $adv = Advocate::where('enrollment_no_str', $query)->first();
//         }

//         if (! $adv) {
//             $digits = preg_replace('/[^0-9]/', '', $query);
//             if ($digits !== '') {
//                 $adv = Advocate::where('enrollment_no', $digits)->first();
//             }
//         }
//     }

//     if (! $adv) {
//         return response()->json(['success' => false, 'message' => 'No matching advocate found.'], 200);
//     }

//     return response()->json(['success' => true, 'data' => $adv], 200);
// };

// Route::post('/advocate/search', $advocateSearchHandler);