const alerts = document.querySelectorAll(".alert");


// console.log("ALERT from KIKI !")
if (alerts) {
  alerts.forEach((alert) => {
    alert.addEventListener("click", () => {
      alert.classList.add("move_to_up");
    });
  });
}

if (alerts) {
  alerts.forEach((alert) => {
    setTimeout(() => {
      alert.classList.add("move_to_up");
    }, 3000);
  });
}
