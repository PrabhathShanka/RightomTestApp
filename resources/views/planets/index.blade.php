@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Star Wars Planets</h1>

        <div class="mb-6 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
            <div class="w-full md:w-1/3">
                <input type="text" id="searchInput" placeholder="Search planets..."
                    class="w-full border border-gray-300 p-3 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <select id="sortSelect"
                    class="border border-gray-300 p-3 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Sort by Diameter</option>
                    <option value="low">Low to High</option>
                    <option value="high">High to Low</option>
                </select>
            </div>
        </div>

        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Diameter</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Terrain</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Population</th>
                    </tr>
                </thead>
                <tbody id="planetsTableBody" class="bg-white divide-y divide-gray-200">
                </tbody>
            </table>
        </div>

        <div id="paginationLinks" class="mt-4"></div>
    </div>

    <script>
        const planetShowBaseUrl = "{{ route('planets.show', ':id') }}";

        $(document).ready(function() {

            let search = '';
            let sort = '';
            let page = 1;

            function fetchPlanets() {
                $.ajax({
                    url: "{{ route('planets.index') }}",
                    method: 'GET',
                    data: {
                        search,
                        sort,
                        page
                    },
                    success: function(res) {
                        let rows = '';
                        res.data.forEach(planet => {
                            rows += `
                                    <tr class="hover:bg-gray-50 cursor-pointer"
                                        onclick="window.location='${planetShowBaseUrl.replace(':id', planet.url.split('/').filter(Boolean).pop())}'">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">${planet.name}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">${planet.diameter}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">${planet.terrain}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">${planet.population}</td>
                                    </tr>
                                `;


                        });
                        $('#planetsTableBody').html(rows);
                        $('#paginationLinks').html(res.pagination);
                    }
                });
            }

            $('#searchInput').on('keyup', function() {
                search = $(this).val();
                page = 1;
                fetchPlanets();
            });

            $('#sortSelect').on('change', function() {
                sort = $(this).val();
                page = 1;
                fetchPlanets();
            });

            $(document).on('click', '#paginationLinks a', function(e) {
                e.preventDefault();
                let url = new URL($(this).attr('href'));
                page = url.searchParams.get('page');
                fetchPlanets();
            });

            fetchPlanets();
        });
    </script>
@endsection
