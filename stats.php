<!DOCTYPE html>
<html lang="en">
<head>
  <?php $sitetitle = 'IP Stats Analyzer'; ?>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $sitetitle; ?></title>
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
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-bottom: 1em;
        }

        th, td {
            padding: 0.75em;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
            text-transform: uppercase;
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

        .chart-container {
            width: 100%;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            margin-top: 1.5em;
        }

        canvas {
            margin: 1em;
            width: 45%;
            max-width: 400px;
            height: auto;
        }

        /* Dropdown styling voor filters */
        .filter-header {
            display: flex;
            justify-content: space-around;
            margin-bottom: 1em;
            flex-wrap: wrap;
        }

        .filter-header select {
            padding: 0.5em;
            margin: 0.5em;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1em;
        }

    /* filter‐select in headers */
    th .column-filter {
      display: block;
      width: 100%;
      margin-top: 0.25em;
      padding: 0.25em;
      font-size: 0.9em;
      box-sizing: border-box;
    }
  </style>
</head>
<body>
  <header>
    <h1><?php echo $sitetitle; ?></h1>
  </header>
  <main>
    <table id="data-table">
      <thead>
        <!-- filter‐row: één select per kolom -->
        <tr class="filter-row">
          <th><select class="column-filter" data-col="0" multiple></select></th>
          <th><select class="column-filter" data-col="1" multiple></select></th>
          <th><select class="column-filter" data-col="2" multiple></select></th>
          <th><select class="column-filter" data-col="3" multiple></select></th>
          <th><select class="column-filter" data-col="4" multiple></select></th>
          <th><select class="column-filter" data-col="5" multiple></select></th>
          <th><!-- geen filter op tijd --></th>
        </tr>
        <tr>
          <th>IP Adres</th>
          <th>ISP</th>
          <th>Stad</th>
          <th>Land</th>
          <th>Continent</th>
          <th>Datum</th>
          <th>Tijd</th>
        </tr>
      </thead>
      <tbody id="table-body">
        <?php
        if (file_exists('ip.txt')) {
          $lines = file('ip.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

          foreach ($lines as $line) {
            preg_match_all('/:\s*([^|]+)/', $line, $matches);
            if (!empty($matches[1])) {
              echo '<tr>';
              echo '<td>' . htmlspecialchars(trim($matches[1][0])) . '</td>'; // IP
              echo '<td>' . htmlspecialchars(trim($matches[1][4])) . '</td>'; // ISP
              echo '<td>' . htmlspecialchars(trim($matches[1][2])) . '</td>'; // Stad
              echo '<td>' . htmlspecialchars(trim($matches[1][3])) . '</td>'; // Land
              echo '<td>' . htmlspecialchars(trim($matches[1][1])) . '</td>'; // Continent
              echo '<td>' . htmlspecialchars(trim($matches[1][6])) . '</td>'; // Datum
              echo '<td>' . htmlspecialchars(trim($matches[1][7])) . '</td>'; // Tijd
              echo '</tr>';
            }
          }
        } else {
          echo '<tr><td colspan="7">Geen gegevens gevonden.</td></tr>';
        }
        ?>
      </tbody>
    </table>

    <!-- je chart‐containers blijven ongewijzigd -->
    <div class="chart-container">
      <canvas id="continentChart"></canvas>
      …
    </div>
  </main>

  <script>
    const table = document.getElementById('data-table');
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    const filters = Array.from(table.querySelectorAll('.column-filter'));

    // Haal unieke waarden per kolom
    function getColumnValues(colIndex) {
      return [...new Set(rows.map(r => r.children[colIndex].textContent.trim()))].sort();
    }

    // Vul elke dropdown met zijn waarden
    filters.forEach(select => {
      const col = +select.dataset.col;
      const values = getColumnValues(col);
      values.forEach(v => {
        const opt = document.createElement('option');
        opt.value = v;
        opt.textContent = v;
        select.appendChild(opt);
      });
    });

    // Filter‐functie: verbergt alle rijen die niet voldoen aan alle actieve filters
    function applyFilters() {
      // voor iedere kolom: welke waarden geselecteerd?
      const active = filters.map(sel =>
        Array.from(sel.selectedOptions).map(o => o.value)
      );

      rows.forEach(row => {
        let visible = true;
        filters.forEach((sel, idx) => {
          if (active[idx].length > 0) {
            const cell = row.children[idx].textContent.trim();
            if (!active[idx].includes(cell)) visible = false;
          }
        });
        row.style.display = visible ? '' : 'none';
      });
    }

    // Event‐listeners op iedere dropdown
    filters.forEach(sel => sel.addEventListener('change', applyFilters));

    // Eentje runnen bij load
    applyFilters();
  </script>
</body>
</html>