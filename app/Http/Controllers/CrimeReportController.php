<?php

namespace App\Http\Controllers;

use App\Models\CrimeDataSource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CrimeReportController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $zipLocation = null;
        $sources = collect();

        if ($user && $user->zip_code) {
            $zipLocation = DB::table('zipcodes')
                ->where('zip', $user->zip_code)
                ->first([
                    'zip',
                    'city',
                    'statename',
                    'stateabv',
                    'country',
                    'lat',
                    'lng',
                ]);
        }

        if ($zipLocation) {
            $normalize = function ($value) {
                $value = strtolower(trim((string) $value));

                if (in_array($value, [
                    'usa',
                    'us',
                    'united states',
                    'united states of america',
                ], true)) {
                    return 'usa';
                }

                return $value;
            };

            $sameCountry = function ($source) use (
                $zipLocation,
                $normalize
            ) {
                return $normalize($source->country)
                    === $normalize($zipLocation->country);
            };

            $sameState = function ($source) use (
                $zipLocation,
                $normalize
            ) {
                $sourceState = $normalize($source->state);

                return $sourceState !== ''
                    && in_array($sourceState, [
                        $normalize($zipLocation->statename),
                        $normalize($zipLocation->stateabv),
                    ], true);
            };

            $sources = CrimeDataSource::query()
                ->where('is_active', true)
                ->get()
                ->filter(function ($source) use (
                    $zipLocation,
                    $normalize,
                    $sameCountry,
                    $sameState
                ) {
                    if (!$sameCountry($source)) {
                        return false;
                    }

                    switch ($source->coverage_type) {
                        case 'zip':
                            return trim((string) $source->zip_code)
                                === trim((string) $zipLocation->zip);

                        case 'city':
                            return $sameState($source)
                                && $normalize($source->city)
                                    === $normalize($zipLocation->city);

                        case 'state':
                            return $sameState($source);

                        default:
                            return false;
                    }
                })
                ->values();
        }

        return view(
            'safety.index',
            compact('zipLocation', 'sources')
        );
    }
}
