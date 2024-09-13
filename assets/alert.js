//  les alertes

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
    }, 4000);
  });
}

// Les avatars

const avatars = document.querySelectorAll(".avatar-img");

if (avatars) {
  avatars.forEach((avatar) => {
    avatar.addEventListener("mouseover", () => {
      avatars.forEach((img) => {
        if (img !== avatar && !img.classList.contains('color-shadow-md') ) {
          img.style.filter = "brightness(70%)";
        }
      });
      avatar.addEventListener("mouseout", () => {
        avatars.forEach((img) => {
          img.style.filter = "brightness(100%)";
        });
      });
    });
  });
}
