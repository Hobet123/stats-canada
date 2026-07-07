<!DOCTYPE html>
<html>
<head>
    <title>Population Explorer</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial;
            margin: 40px;
            background: #f6f7fb;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            max-width: 900px;
            margin: auto;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        select {
            padding: 8px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="card">
    <h2>📊 Population Explorer</h2>

    <select id="geo">
        <!-- <option>Canada</option>
        <option>Ontario</option>
        <option>Quebec</option>
        <option>Alberta</option>
        <option>British Columbia</option>
        <option>Manitoba</option>
        <option>Nunavut</option>
        <option>Yukon</option> -->

    </select>
    

    <canvas id="chart"></canvas>
</div>


<script>

        async function loadGeos() {
        const res = await fetch('/api/population/geos.php');
        const geos = await res.json();

        const geo= document.getElementById('geo');


        geos.forEach(g => {

            const opt1 = document.createElement('option');
            opt1.value = g;
            opt1.textContent = g;
            geo.appendChild(opt1);

        });

        // default selections
        geo.value = 'Canada';

    }

    loadGeos();
</script>


<script>
let chart;

async function loadData(geo) {
    const res = await fetch(`/api/population_old2.php?geo=${geo}`);
    const json = await res.json();

    const labels = json.series.map(i => i.date);
    const values = json.series.map(i => i.value);

    if (chart) chart.destroy();

    chart = new Chart(document.getElementById('chart'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: geo + ' Population',
                data: values,
                borderWidth: 2,
                tension: 0.3
            }]
        }
    });
}

document.getElementById('geo').addEventListener('change', e => {
    loadData(e.target.value);
});

loadData('Canada');
</script>

</body>
</html>