<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Toronto Area Map</title>

<link
  rel="stylesheet"
  href="https://unpkg.com/leaflet/dist/leaflet.css"
/>

<style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
    }

    #map {
        width: 100%;
        height: 100vh;
    }
</style>
</head>
<body>

<div id="map"></div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    // Center map around Toronto
    const map = L.map('map').setView([43.6532, -79.3832], 10);

    // OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // General area circle (not exact location)
    L.circle([43.6532, -79.3832], {
        color: 'blue',
        fillColor: '#3399ff',
        fillOpacity: 0.3,
        radius: 15000 // 15 km
    }).addTo(map);

    // Optional marker
    L.marker([43.6532, -79.3832])
        .addTo(map)
        .bindPopup('Somewhere around Toronto')
        .openPopup();
</script>

</body>
</html>