<?php
  require_once "./setup.php";
?>

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

    // Start generating the calendar
    // Handle month and year wrapping
    // if ($month < 1) {
    //   $month = 12;
    //   $year--;
    // } elseif ($month > 12) {
    //   $month = 1;
    //   $year++;
    // }

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
            <?php foreach ($days_of_week as $week): ?>
              <th>
                <?php echo $week; ?>
              </th>
            <?php endforeach; ?>
          </tr>
        <thead>

        <!-- Print empty cells for days before the first day of the month  -->
        <?php for ($i = 0; $i < $firstDayOfMonth; $i++) { ?>
          <?php echo "<td></td>"; ?>
        <?php } ?>

        <!-- Print the days of the month -->
        <?php for ($i = $firstDayOfMonth; $i < 7; $i++) { ?>
          <?php if ($day <= $calendar_days) { ?>
            <?php echo "<td onclick='openForm($day-$month-$year)'>$day</td>"; ?>
            <?php $day++; ?>
          <?php } ?>
        <?php } ?>

        <!-- Print the remaining weeks -->
        <?php while ($day <= $calendar_days) { ?>
          <tr>

          <?php for ($i = 0; $i < 7; $i++) { ?>
            <?php if ($day <= $calendar_days) { ?>
              <?php echo "<td onclick='openForm($day)'>$day</td>"; ?>
              <?php $day++; ?>
            <?php } else { ?>
              <?php echo "<td></td>"; ?>
            <?php } ?>
            <?php } ?>

          </tr>
        <?php } ?>
      </table>
    </div>

    <!-- Appointment Form Popup -->
    <div class="form-popup" id="appointmentForm">
      <form action="save_appointment.php" method="post">
        <h2>Add Appointment</h2>
        <input type="hidden" id="appointmentDay" name="day">
        <label for="title">Name:</label>
        <input type="text" id="name" name="name" required>
        <label for="email">Email</label>
        <input type="email" name="email" required></input>
        <label for="email">Phone Number</label>
        <input type="tel" name="phone" required></input>
        <button type="submit">Save</button>
        <button type="button" class="close" onclick="closeForm()">Cancel</button>
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
