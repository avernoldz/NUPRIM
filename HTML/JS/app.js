var config = {
  cUrl: "https://psgc.gitlab.io/api/regions/",
  cUrlProv: "https://psgc.gitlab.io/api/provinces/",
  cUrlBrgy: "https://psgc.gitlab.io/api/municipalities/",
  // ckey: "NHhvOEcyWk50N2Vna3VFTE00bFp3MjFKR0ZEOUhkZlg4RTk1MlJlaA==",
};

var countrySelect = document.querySelector(".country"),
  stateSelect = document.querySelector(".state"),
  citySelect = document.querySelector(".city"),
  brgySelect = document.querySelector(".brgy");

function loadCountries() {
  let apiEndPoint = config.cUrl;

  fetch(apiEndPoint, { headers: {} })
    .then((Response) => Response.json())
    .then((data) => {
      data.forEach((country) => {
        const option = document.createElement("option");
        option.id = country.code;
        option.value = country.regionName;
        option.textContent = country.regionName;
        countrySelect.appendChild(option);
      });
    })
    .catch((error) => console.error("Error loading countries:", error));

  stateSelect.disabled = true;
  citySelect.disabled = true;
  brgySelect.disabled = true;
  stateSelect.style.pointerEvents = "none";
  citySelect.style.pointerEvents = "none";
  brgySelect.style.pointerEvents = "none";
}

function loadProvince() {
  stateSelect.disabled = false;
  citySelect.disabled = true;
  stateSelect.style.pointerEvents = "auto";
  citySelect.style.pointerEvents = "none";
  brgySelect.style.pointerEvents = "none";

  const selectedCountryCode =
    countrySelect.options[countrySelect.selectedIndex].id;

  console.log(selectedCountryCode);
  stateSelect.innerHTML = '<option value="">Select Province</option>'; // for clearing the existing states
  citySelect.innerHTML = '<option value="">Select City</option>'; // Clear existing city options
  brgySelect.innerHTML = '<option value="">Select Brgy.</option>'; // Clear existing brgy options

  fetch(`${config.cUrl}/${selectedCountryCode}/provinces`, {
    headers: {},
  })
    .then((response) => response.json())
    .then((data) => {
      console.log(data);

      data.forEach((state) => {
        const option = document.createElement("option");
        option.id = state.code;
        option.value = state.name;
        option.textContent = state.name;
        stateSelect.appendChild(option);
      });
    })
    .catch((error) => console.error("Error loading countries:", error));
}

function loadCities() {
  citySelect.disabled = false;
  citySelect.style.pointerEvents = "auto";

  const selectedStateCode = stateSelect.options[stateSelect.selectedIndex].id;
  console.log(selectedStateCode);

  citySelect.innerHTML = '<option value="">Select City</option>'; // Clear existing city options

  fetch(`${config.cUrlProv}/${selectedStateCode}/municipalities`, {
    headers: {},
  })
    .then((response) => response.json())
    .then((data) => {
      console.log(data);

      data.forEach((city) => {
        const option = document.createElement("option");
        option.id = city.code;
        option.value = city.name;
        option.textContent = city.name;
        citySelect.appendChild(option);
      });
    });
}

function loadBrgy() {
  brgySelect.disabled = false;
  brgySelect.style.pointerEvents = "auto";

  const selectedCity = citySelect.options[citySelect.selectedIndex].id;

  console.log(selectedCity);
  brgySelect.innerHTML = '<option value="">Select Brgy.</option>'; // Clear existing city options

  fetch(`${config.cUrlBrgy}/${selectedCity}/barangays`, {
    headers: {},
  })
    .then((response) => response.json())
    .then((data) => {
      console.log(data);

      data.forEach((brgy) => {
        const option = document.createElement("option");
        option.value = brgy.name;
        option.textContent = brgy.name;
        brgySelect.appendChild(option);
      });
    });
}

toastr.options = {
  closeButton: false,
  debug: false,
  newestOnTop: false,
  progressBar: false,
  positionClass: "toast-top-right",
  preventDuplicates: false,
  onclick: null,
  showDuration: "300",
  hideDuration: "1000",
  timeOut: "5000",
  extendedTimeOut: "1000",
  showEasing: "swing",
  hideEasing: "linear",
  showMethod: "fadeIn",
  hideMethod: "fadeOut",
};

if (typeof alertMessage !== "undefined") {
  alertMessage;
}

if (window.history.replaceState) {
  window.history.replaceState(null, null, window.location.href.split("?")[0]);
}

$("#npassword, #cpassword").keyup(function () {
  const pass = $("#npassword").val();
  const cpass = $("#cpassword").val();
  const message = $(".cpass");

  if (pass !== cpass) {
    message.show();
    message
      .removeClass("text-success")
      .addClass("text-danger")
      .text("Passwords do not match.");
    $("#save").attr("disabled", true);
  } else {
    message.show();
    message
      .removeClass("text-danger")
      .addClass("text-success")
      .text("Passwords match");
    $("#save").attr("disabled", false);
  }

  console.log(pass, cpass, settings);
});

$("#opassword").keyup(function () {
  const old = $("#opassword").val();
  const input = $("#opassword2").val();

  $.ajax({
    url: "action/settings.php", // URL to your server-side script
    type: "POST",
    data: {
      old: old,
      input: input,
    },
    success: function (response) {
      console.log(old);
      console.log(response);
      if (response == "correct") {
        $("#save").attr("disabled", false);
        $(".opass")
          .text("Old password is correct.")
          .removeClass("text-danger")
          .addClass("text-success")
          .show();
      } else {
        $("#save").attr("disabled", true);
        $(".opass")
          .text("Old password is incorrect.")
          .removeClass("text-success")
          .addClass("text-danger")
          .show();
      }
    },
    error: function () {
      console.log("ERR");
      $(".opass")
        .text("An error occurred. Please try again.")
        .addClass("text-danger")
        .show();
    },
  });
});

function initializeFlatpickr(selector) {
  $(selector).flatpickr({
    dateFormat: "F j, Y",
    onChange: function (selectedDates, dateStr, instance) {
      $(selector).text(dateStr); // Update the element with the formatted date
    },
  });
}
