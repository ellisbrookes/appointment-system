<?php

enum AlertVariants: string
{
  case SUCCESS = "success";
  case DANGER = "danger";
  case WARNING = "warning";
  case INFO = "info";
}

class Alert
{
  public static function setAlert(AlertVariants $variant, string $message): void
  {
    $_SESSION[$variant->value] = $message;
  }

  public static function renderAlert(): void
  {
    foreach (AlertVariants::cases() as $index => $variant) {
      if (isset($_SESSION[$variant->value])) {
        echo "<div class='notification-banner' id='notificationBanner-{$index}' role='alert'>";
        echo "<div class='alert alert-{$variant->value}'>{$_SESSION[$variant->value]}</div>";
        echo "<span class='close-btn' onclick='closeBanner({$index})'>×</span>";
        echo "</div>";

        // remove the alert on refresh
        unset($_SESSION[$variant->value]);
      }
    }
  }
}
?>

<script>
  function closeBanner(index) {
    const alertElement = document.getElementById(`notificationBanner-${index}`);

    if (alertElement) {
      alertElement.style.display = 'none';
    }
  }
 </script>
