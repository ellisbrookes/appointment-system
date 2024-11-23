// appointment form popup
function openForm(year, month, day) {
  const convertDay = day.toString().padStart(2, '0');
  const date = `${year}-${month}-${convertDay}`;

  document.getElementById("appointmentForm").style.display = "block";
  document.getElementById("appointmentDate").value = date;
  console.log("date: ", date)
}

function closeForm() {
  document.getElementById("appointmentForm").style.display = "none";
}