<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;

class PlanetController extends Controller
{
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $search = $request->query('search', '');
                $sort = $request->query('sort', '');
                $page = $request->query('page', 1);

                $planets = [];
                $url = 'https://swapi.dev/api/planets/?page=' . $page;

                $response = Http::get($url)->json();

                foreach ($response['results'] as $planet) {

                    $name = strtolower($planet['name'] ?? '');
                    $diameter = strtolower($planet['diameter'] ?? '');
                    $terrain = strtolower($planet['terrain'] ?? '');
                    $population = strtolower($planet['population'] ?? '');

                    if (

                        $diameter === 'unknown' || $diameter === '0' ||
                        $terrain === 'unknown' || $terrain === '0' ||
                        $population === 'unknown' || $population === '0'
                    ) {
                        continue;
                    }

                    if ($search == '' || str_contains($name, strtolower($search))) {
                        $planets[] = $planet;
                    }
                }

                if ($sort == 'low') {
                    usort($planets, fn($a, $b) => (int)$a['diameter'] <=> (int)$b['diameter']);
                } elseif ($sort == 'high') {
                    usort($planets, fn($a, $b) => (int)$b['diameter'] <=> (int)$a['diameter']);
                }

                $paginator = new LengthAwarePaginator(
                    $planets,
                    $response['count'],
                    10,
                    $page,
                    ['path' => url()->current()]
                );

                return response()->json([
                    'data' => $paginator->items(),
                    'pagination' => (string) $paginator->links(),
                ]);
            }

            return view('planets.index');
        } catch (\Exception $e) {
            info($e);
            return redirect()->route('planets.index')->with('error', 'Planet not found');
        }
    }

    public function show($id)
    {
        try {

            $planet = Http::get("https://swapi.dev/api/planets/{$id}/")->json();

            $radius = $planet['diameter'] / 2;
            $rotation_hours = $planet['rotation_period'] ?: 24;
            $planet['rotation_speed'] = (2 * pi() * $radius) / $rotation_hours;

            return view('planets.show', compact('planet'));
        } catch (\Exception $e) {
            info($e);
            return redirect()->route('planets.index')->with('error', 'Planet not found');
        }
    }
}
