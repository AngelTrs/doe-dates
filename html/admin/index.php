<?php declare(strict_types=1);
define('ROOT','/var/www');
require ROOT . '/basic-auth.php';
requireAuth();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/css/pico.min.css">
  <title>DOE workdays - admin</title>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body>
  <main class="container">
    <div 
      x-data="{ 
        data: [],
        addNewRow() {
          this.data.holidays.push(['','',''])
        },
        removeRow(index) {
          this.data.holidays.splice(index,1)
        }
      }" 
      x-init="
        data = await (await fetch('/api/data.php')).json()
      ">
      <form method="POST" action="/api/data.php">
      <label for="email">Last Day of School Year</label>
      <input type="text" name="last_day" :value="data.last_day" required>

      <label for="holiday_table">School Holidays</label>
      <table name="holiday_table">
        <thead>
          <tr>
            <th scope="col">Name</th>
            <th scope="col">Date</th>
            <th scope="col">Duration</th>
          </tr>
        </thead>
        <tbody>
          <template x-for="(holiday, index) in data.holidays">
            <tr>
              <td>
                <input type="text" :name="'holidays['+index+'][name]'" :value="holiday.name">
              </td>
              <td> 
                <input type="text" :name="'holidays['+index+'][date]'" :value="holiday.date">
              </td>
              <td>
                <input type="text" :name="'holidays['+index+'][duration]'" :value="holiday.duration">
              </td>
              <td>
                <button type="button" x-on:click="removeRow(index)">X</button>
              </td>
            </tr>
          </template>
        </tbody>
      </table>
      <button type="button" x-on:click="addNewRow()">Add Row</button>
      <button type="submit">Save Changes</button>
      </form>

    </div> 
  </main>
</body>
</html>
