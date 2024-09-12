<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div class="m-5">
        @foreach ($categories as $category)
            <button onclick="fetchTechnologies('{{ $category->id }}')" class="btn btn-primary">{{ $category->name }}</button>
        @endforeach
    </div>

    <script>
        function fetchTechnologies(categoryId) {
    // Fetch data technologies berdasarkan category_id
    fetch(`/api/technologies/${categoryId}`)
        .then(response => response.json())
        .then(technologies => {
            // Map data technologies ke format yang sesuai dengan radar ThoughtWorks
            const formattedData = technologies.map(tech => ({
                quadrant: tech.quadrant, // pastikan quadrant sesuai dengan nilai yang diterima radar
                ring: tech.ring,         // pastikan ring sesuai dengan nilai yang diterima radar
                label: tech.name,        // pastikan label sesuai dengan nama teknologi
                active: true,
                moved: 0
            }));

            // Convert data formatted ke JSON string
            const jsonData = JSON.stringify(formattedData);

            // Encode JSON data ke URL format
            const encodedData = encodeURIComponent(jsonData);

            // Redirect ke halaman Tech Radar dengan data JSON
            window.open(`https://radar.thoughtworks.com/?data=${encodedData}`, '_blank');
        })
        .catch(error => console.error('Error fetching technologies:', error));
        }
    </script>
</body>
</html>
