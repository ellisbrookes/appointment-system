<?php
require_once "../setup.php";

$title = "Dashboard";

include "../partials/shared/head.php";
include "../partials/shared/alerts.php";
include "../partials/dashboard/sidebar.php";

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
$day = 1;
$monthName = date("F", strtotime("$year-$month"));
$firstDayOfMonth = date("w", strtotime("$year-$month-$day"));

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

// Render alerts
Alert::renderAlert();
?>

<main>
  <div class="page-content">

    <!-- Calendar Content -->
    <div class="calendar">
      <h1 class="text-center"><?php echo "$monthName $year"; ?></h1>

      <div class="pagination">
        <a href="?month=<?php echo $prevMonth; ?>&year=<?php echo $prevYear; ?>">Previous</a>
        <a href="?month=<?php echo $nextMonth; ?>&year=<?php echo $nextYear; ?>">Next</a>
      </div>

      <table>
        <thead>
          <tr>
            <?php foreach ($days_of_week as $week) {
              echo "<th>$week</th>";
            } ?>
          </tr>
        </thead>
        <tbody>
          <tr>
            <!-- Print empty cells for days before the first day of the month  -->
            <?php for ($i = 0; $i < $firstDayOfMonth; $i++): ?>
              <td></td>
            <?php endfor; ?>

            <!-- Print the days of the month -->
            <?php for ($i = $firstDayOfMonth; $i < 7; $i++): ?>
              <?php if ($day <= $calendar_days): ?>
                <td onclick='openForm(<?php echo $year; ?>, <?php echo $month; ?>, <?php echo $day; ?>)'><?php echo $day; ?></td>
                <?php $day++; ?>
              <?php endif; ?>
            <?php endfor; ?>
          </tr>

          <!-- Print the remaining weeks -->
          <?php while ($day <= $calendar_days): ?>
            <tr>
              <?php for ($i = 0; $i < 7; $i++): ?>
                <?php if ($day <= $calendar_days): ?>
                  <td onclick='openForm(<?php echo $year; ?>, <?php echo $month; ?>, <?php echo $day; ?>)'><?php echo $day; ?></td>
                  <?php $day++; ?>
                <?php else: ?>
                  <td></td>
                <?php endif; ?>
              <?php endfor; ?>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Appointment Form Popup -->
  <div class="modal" id="appointmentForm">
    <form action="appointments/save.php" method="post">
      <h2>Add Appointment</h2>

      <label for="service">Service:</label>
      <br />
      <select name="service" id="service" required>
        <option name="General council">General council (£59)</option>
        <option name="General nurse">General nurse (£999)</option>
        <option name="Ambulance ride">Ambulance ride (£10,000) US only</option>
      </select>
      <br />

      <label for="date">Appointment Date:</label>
      <input type="date" id="appointmentDate" name="date" required>

      <input type="hidden" name="user_id" value="1">

      <button type="submit">Submit</button>
      <button type="button" class="close" onclick="closeForm()">Cancel</button>
    </form>
  </div>
</main>

<?php include "../partials/shared/footer.php"; ?>
