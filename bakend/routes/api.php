<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Advocate;

// API (stateless) advocate search — optimized for performance.
Route::post('/advocate/search', function (Request $request) {
	$data = $request->validate([
		'mode' => 'required|in:name,enrollment',
		'query' => 'required|string|max:255',
	]);

	$mode = $data['mode'];
	$query = trim($data['query']);

	$selectCols = ['id', 'name', 'enrollment_no_str', 'enrollment_no', 'year', 'father_name', 'district', 'bar_association', 'gender'];

	// Suggestion mode for name autocomplete — support pagination and smaller page sizes
	if ($mode === 'name' && $request->boolean('suggest')) {
		$per_page = max(5, min(100, (int) $request->input('per_page', 10)));
		$page = max(1, (int) $request->input('page', 1));
		$offset = ($page - 1) * $per_page;

		$suggestions = Advocate::where('name', 'like', $query . '%')
			->orderBy('name')
			->offset($offset)
			->limit($per_page)
			->get($selectCols);

		return response()->json(['success' => true, 'suggestions' => $suggestions], 200);
	}

	$adv = null;

	if ($mode === 'name') {
		$adv = Advocate::select($selectCols)
			->where('name', 'like', $query . '%')
			->first();
	} else {
		if (str_contains($query, '/')) {
			[$enoPart, $yearPart] = array_map('trim', explode('/', $query, 2));
			$yearDigits = preg_replace('/[^0-9]/', '', $yearPart);

			if ($enoPart !== '') {
				$adv = Advocate::select($selectCols)
					->where('enrollment_no_str', $enoPart)
					->when($yearDigits !== '', fn($q) => $q->where('year', $yearDigits))
					->first();
			}

			if (! $adv) {
				$digits = preg_replace('/[^0-9]/', '', $enoPart);
				if ($digits !== '') {
					$adv = Advocate::select($selectCols)
						->where('enrollment_no', $digits)
						->when($yearDigits !== '', fn($q) => $q->where('year', $yearDigits))
						->first();
				}
			}
		}

		if (! $adv) {
			$adv = Advocate::select($selectCols)->where('enrollment_no_str', $query)->first();
		}

		if (! $adv) {
			$digits = preg_replace('/[^0-9]/', '', $query);
			if ($digits !== '') {
				$adv = Advocate::select($selectCols)->where('enrollment_no', $digits)->first();
			}
		}
	}

	if (! $adv) {
		return response()->json(['success' => false, 'message' => 'No matching advocate found.'], 200);
	}

	return response()->json(['success' => true, 'data' => $adv], 200);
});

