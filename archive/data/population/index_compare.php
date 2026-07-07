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
        <option>Yukon</option>
        <option>Prince Edward Island</option>
        <option>Nova Scotia</option>
        <option>Northwest Territories</option>
        <option>Saskatchewan</option>
        <option>New Brunswick</option> -->

    </select>


</div>

<div class="compare">
    <select id="geo1"></select>
    <select id="geo2"></select>

    <button onclick="compare()">Compare</button>
    </div>

<canvas id="chart"></canvas>

<script>
    let chart;

    async function loadData(geo) {
        const res = await fetch(`/api/population.php?geo=${geo}`);
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

    async function compare() {
        const g1 = document.getElementById('geo1').value;
        const g2 = document.getElementById('geo2').value;

        const res = await fetch(`/api/population.php?geo=${g1},${g2}`);
        const json = await res.json();

        if (chart) chart.destroy();

        const datasets = json.data.map(item => ({
            label: item.geo,
            data: item.series.map(i => i.value),
            borderWidth: 2,
            tension: 0.3
        }));

        const labels = json.data[0].series.map(i => i.date);

        chart = new Chart(document.getElementById('chart'), {
            type: 'line',
            data: {
                labels,
                datasets
            }
        });
    }

    async function loadGeos() {
        const res = await fetch('/api/geos.php');
        const geos = await res.json();

        const geo1 = document.getElementById('geo1');
        const geo2 = document.getElementById('geo2');

        geos.forEach(g => {

            const opt1 = document.createElement('option');
            opt1.value = g;
            opt1.textContent = g;
            geo1.appendChild(opt1);

            const opt2 = document.createElement('option');
            opt2.value = g;
            opt2.textContent = g;
            geo2.appendChild(opt2);
        });

        // default selections
        geo1.value = 'Ontario';
        geo2.value = 'Quebec';
    }

    loadGeos();
</script>

</body>
</html>