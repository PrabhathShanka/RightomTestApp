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


        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" id="planetsGrid">

        </div>


        <div id="paginationLinks" class="mt-6 flex justify-center md:justify-end items-center">
        </div>
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
                        let cards = '';
                        res.data.forEach(planet => {
                            const planetId = planet.url.split('/').filter(Boolean).pop();
                            cards += `
                            <div class="bg-white shadow-lg rounded-lg p-4 hover:shadow-xl transition duration-300 cursor-pointer"
                                onclick="window.location='${planetShowBaseUrl.replace(':id', planetId)}'">
                                <h2 class="text-xl font-bold mb-2 text-gray-800">${planet.name}</h2>
                                <p class="text-gray-600"><strong>Diameter:</strong> ${planet.diameter}</p>
                                <p class="text-gray-600"><strong>Terrain:</strong> ${planet.terrain}</p>
                                <p class="text-gray-600"><strong>Population:</strong> ${planet.population}</p>
                            </div>
                        `;
                        });

                        $('#planetsGrid').html(cards);
                        $('#paginationLinks').html(res.pagination);
                    },
                    error: function() {
                        $('#planetsGrid').html(
                            '<p class="text-red-500 text-center">Failed to load planets. Please try again.</p>'
                        );
                        $('#paginationLinks').html('');
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
