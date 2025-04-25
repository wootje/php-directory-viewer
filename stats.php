<!DOCTYPE html>
<html lang="en">

<head>
    <?php $sitetitle = 'IP Stats Analyzer'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $sitetitle; ?></title>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Algemene styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }

        header {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 1.5em 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5em;
        }

        main {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Tabel styling */
        table {
            width: 100%;
            margin-bottom: 2em;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            padding: 0.75em;
        }

        th .filter {
            margin-top: 5px;
        }

        th .filter select {
            width: 90%;
            padding: 0.4em;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 0.9em;
            background-color: #fff;
            cursor: pointer;
        }

        td {
            padding: 0.75em;
            text-align: center;
            border: 1px solid #ddd;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:nth-child(odd) {
            background-color: #fff;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        /* Grafiek styling */
        .graph-section {
            text-align: center;
            margin-top: 2em;
            padding: 1em;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Flex container voor grafieken */
        .graph-row {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap; /* Grafieken onder elkaar bij kleinere schermen */
            margin-bottom: 2em;
        }

        /* Grafiek styling */
        canvas {
            margin: 10px auto;
            max-width: 45%; /* Grafieken kleiner gemaakt */
            height: auto;
        }

        /* Responsieve aanpassingen */
        @media (max-width: 768px) {
            canvas {
                max-width: 90%; /* Grafieken onder elkaar op mobiele apparaten */
            }
        }
    </style>
</head>

<body>
    <header>
        <h1><?php echo $sitetitle; ?></h1>
    </header>
    <main>
        <!-- Tabel -->
        <table>
            <!-- Rij met kolomtitels -->
            <tr>
                <th>IP Adres</th>
                <th>Datum</th>
                <th>Continent</th>
                <th>Land</th>
                <th>Regio</th>
                <th>Stad</th>
                <th>ISP</th>
                <th>Valuta</th>
            </tr>
            <!-- Rij met filters -->
            <tr>
                <td>
                    <div class="filter">
                        <select id="ipFilter"></select>
                    </div>
                </td>
                <td>
                    <div class="filter">
                        <select id="dateFilter"></select>
                    </div>
                </td>
                <td>
                    <div class="filter">
                        <select id="continentFilter"></select>
                    </div>
                </td>
                <td>
                    <div class="filter">
                        <select id="countryFilter"></select>
                    </div>
                </td>
                <td>
                    <div class="filter">
                        <select id="regionFilter"></select>
                    </div>
                </td>
                <td>
                    <div class="filter">
                        <select id="cityFilter"></select>
                    </div>
                </td>
                <td>
                    <div class="filter">
                        <select id="ispFilter"></select>
                    </div>
                </td>
                <td>
                    <div class="filter">
                        <select id="currencyFilter"></select>
                    </div>
                </td>
            </tr>
            <!-- Dynamische inhoud -->
            <tbody id="dataTable"></tbody>
        </table>

        <!-- Grafieken onderaan de pagina -->
        <section class="graph-section">
            <h3>Grafieken</h3>
            <div class="graph-row">
                <canvas id="countryChart"></canvas>
                <canvas id="regionChart"></canvas>
            </div>
            <div class="graph-row">
                <canvas id="cityChart"></canvas>
                <canvas id="ispChart"></canvas>
            </div>
        </section>
    </main>

    <script>
        // Dynamische data ophalen van data.php
        fetch('data.php')
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }

                // Unieke waarden verzamelen en dropdowns vullen
                const populateDropdown = (id, values) => {
                    const dropdown = document.getElementById(id);
                    dropdown.innerHTML = '<option value="">Alle opties</option>';
                    values.forEach(value => {
                        const option = document.createElement('option');
                        option.value = value;
                        option.textContent = value;
                        dropdown.appendChild(option);
                    });
                };

                populateDropdown('ipFilter', [...new Set(data.map(d => d.ip))]);
                populateDropdown('dateFilter', [...new Set(data.map(d => d.date))]);
                populateDropdown('continentFilter', [...new Set(data.map(d => d.continent))]);
                populateDropdown('countryFilter', [...new Set(data.map(d => d.country))]);
                populateDropdown('regionFilter', [...new Set(data.map(d => d.region))]);
                populateDropdown('cityFilter', [...new Set(data.map(d => d.city))]);
                populateDropdown('ispFilter', [...new Set(data.map(d => d.isp))]);
                populateDropdown('currencyFilter', [...new Set(data.map(d => d.currency))]);

                // Functie om tabel te filteren
                const renderTable = (filterValues = {}) => {
                    const dataTable = document.getElementById('dataTable');
                    dataTable.innerHTML = ''; // Leegmaken

                    data.filter(entry => {
                        return Object.keys(filterValues).every(key => {
                            const value = filterValues[key];
                            return !value || entry[key] === value;
                        });
                    }).forEach(entry => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${entry.ip}</td>
                            <td>${entry.date}</td>
                            <td>${entry.continent}</td>
                            <td>${entry.country}</td>
                            <td>${entry.region}</td>
                            <td>${entry.city}</td>
                            <td>${entry.isp}</td>
                            <td>${entry.currency}</td>
                        `;
                        dataTable.appendChild(row);
                    });
                };

                // Dropdown events koppelen aan filtering
                ['ipFilter', 'dateFilter', 'continentFilter', 'countryFilter', 'regionFilter', 'cityFilter', 'ispFilter', 'currencyFilter'].forEach(id => {
                    const dropdown = document.getElementById(id);
                    dropdown.addEventListener('change', () => {
                        const filters = {
                            ip: document.getElementById('ipFilter').value,
                            date: document.getElementById('dateFilter').value,
                            continent: document.getElementById('continentFilter').value,
                            country: document.getElementById('countryFilter').value,
                            region: document.getElementById('regionFilter').value,
                            city: document.getElementById('cityFilter').value,
                            isp: document.getElementById('ispFilter').value,
                            currency: document.getElementById('currencyFilter').value
                        };
                        renderTable(filters);
                    });
                });

                // Initiale tabel vullen
                renderTable();
            })
            .catch(error => console.error('Error:', error));
    </script>
</body>

</html>
