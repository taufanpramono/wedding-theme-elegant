(function () {
  var navToggle = document.querySelector(".wew-nav-toggle");
  var nav = document.querySelector(".wew-navigation");

  if (navToggle && nav) {
    navToggle.addEventListener("click", function () {
      var expanded = navToggle.getAttribute("aria-expanded") === "true";
      navToggle.setAttribute("aria-expanded", expanded ? "false" : "true");
      nav.classList.toggle("is-open", !expanded);
      document.body.classList.toggle("wew-menu-open", !expanded);
    });

    nav.addEventListener("click", function (event) {
      if (event.target && event.target.tagName === "A") {
        navToggle.setAttribute("aria-expanded", "false");
        nav.classList.remove("is-open");
        document.body.classList.remove("wew-menu-open");
      }
    });
  }

  var countdown = document.querySelector("[data-countdown]");

  if (!countdown) {
    return;
  }

  var target = new Date(countdown.getAttribute("data-countdown")).getTime();
  var daysEl = countdown.querySelector("[data-days]");
  var hoursEl = countdown.querySelector("[data-hours]");
  var minutesEl = countdown.querySelector("[data-minutes]");
  var secondsEl = countdown.querySelector("[data-seconds]");

  function pad(value) {
    return String(value).padStart(2, "0");
  }

  function updateCountdown() {
    var diff = target - Date.now();

    if (!Number.isFinite(target) || diff <= 0) {
      if (daysEl) daysEl.textContent = "00";
      if (hoursEl) hoursEl.textContent = "00";
      if (minutesEl) minutesEl.textContent = "00";
      if (secondsEl) secondsEl.textContent = "00";
      countdown.classList.add("is-complete");
      return;
    }

    var seconds = Math.floor(diff / 1000);
    var days = Math.floor(seconds / 86400);
    var hours = Math.floor((seconds % 86400) / 3600);
    var minutes = Math.floor((seconds % 3600) / 60);
    var remainingSeconds = seconds % 60;

    if (daysEl) daysEl.textContent = pad(days);
    if (hoursEl) hoursEl.textContent = pad(hours);
    if (minutesEl) minutesEl.textContent = pad(minutes);
    if (secondsEl) secondsEl.textContent = pad(remainingSeconds);
  }

  updateCountdown();
  window.setInterval(updateCountdown, 1000);
})();

