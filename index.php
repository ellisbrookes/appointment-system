<?php
require_once "./setup.php"; ?>

<!DOCTYPE html>
<html>
  <head>
      <title>Appointments Calendar</title>
      <link rel="stylesheet" href="stylesheets/styles.css">
  </head>
  <body>
    <?php
    // Get the current month and year, or the ones passed via URL
    $month = isset($_GET["month"]) ? (int) $_GET["month"] : date("m");
    $year = isset($_GET["year"]) ? (int) $_GET["year"] : date("Y");

    // Get the number of days in the current month
    $calendar_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

    // Array for the days of the week
    $days_of_week = [
      "Sunday",
      "Monday",
      "Tuesday",
      "Wednesday",
      "Thursday",
      "Friday",
      "Saturday",
    ];

    // Get the name of the current month and the first day of the month
    $monthName = date("F", strtotime("$year-$month"));
    $firstDayOfMonth = date("w", strtotime("$year-$month-01"));
    $day = 1;

    // Generate pagination links for previous and next month
    $prevMonth = $month - 1;
    $prevYear = $year;

    if ($prevMonth < 1) {
      $prevMonth = 12;
      $prevYear--;
    }

    $nextMonth = $month + 1;
    $nextYear = $year;

    if ($nextMonth > 12) {
      $nextMonth = 1;
      $nextYear++;
    }
    ?>

    <div class="calendar">
      <h1><?php echo "$monthName $year"; ?></h1>

      <div class="pagination">
        <a href="?month=' . <?php $prevMonth; ?> . "&year=" . <?php $prevYear; ?> . '">Previous</a>
        <a href="?month=' . <?php $nextMonth; ?> . "&year=" . <?php $nextYear; ?> . '">Next</a>
      </div>

      <table>
        <thead>
          <tr>
            <?php foreach ($days_of_week as $week) {
              echo "<th>$week</th>";
            } ?>
          </tr>
        <thead>

        <!-- Print empty cells for days before the first day of the month  -->
        <?php for ($i = 0; $i < $firstDayOfMonth; $i++) {
          echo "<td></td>";
        } ?>

        <!-- Print the days of the month -->
        <?php for ($i = $firstDayOfMonth; $i < 7; $i++): ?>
          <?php if ($day <= $calendar_days): ?>
            <?php echo "<td onclick='openForm($day-$month-$year)'>$day</td>"; ?>
            <?php $day++; ?>
          <?php endif; ?>
        <?php endfor; ?>

        <!-- Print the remaining weeks -->
        <?php while ($day <= $calendar_days): ?>
          <tr>
            <?php for ($i = 0; $i < 7; $i++): ?>
              <?php if ($day <= $calendar_days) { ?>
                <?php echo "<td onclick='openForm($day)'>$day</td>"; ?>
                <?php $day++; ?>
              <?php } else { ?>
                <?php echo "<td></td>"; ?>
              <?php } ?>
            <?php endfor; ?>
          </tr>
        <?php endwhile; ?>
      </table>
    </div>

    <!-- Appointment Form Popup -->
    <div class="form-popup" id="appointmentForm">
      <form action="save_appointment.php" method="post">
        <h2>Add Appointment</h2>
        <label for="service">Service:</label>
        <input type="text" id="service" name="service" required>
        
        <label for="date">Appointment Date:</label>
        <input type="date" id="date" name="date" required>
        
        <input type="hidden" name="user_id" value="1">
        
        <button type="submit">Save</button>
      </form>
    </div>

    <script>
      function openForm(day) {
        document.getElementById("appointmentForm").style.display = "block";
        document.getElementById("appointmentDay").value = day;
      }

      function closeForm() {
        document.getElementById("appointmentForm").style.display = "none";
      }
    </script>
  </body>
</html>
