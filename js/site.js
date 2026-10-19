(function () {
  var toggle = document.querySelector(".nav-toggle");
  var nav = document.querySelector(".site-nav");
  if (toggle && nav) {
    toggle.addEventListener("click", function () {
      var open = nav.classList.toggle("is-open");
      toggle.setAttribute("aria-expanded", open ? "true" : "false");
      toggle.textContent = open ? "Close" : "Menu";
    });
  }

  var year = document.getElementById("year");
  if (year) year.textContent = String(new Date().getFullYear());

  var form = document.querySelector("form.enquiry");
  var banner = document.getElementById("form-banner");
  if (!form || !banner) return;

  form.addEventListener("submit", function (event) {
    event.preventDefault();
    var data = new FormData(form);
    if ((data.get("company_website") || "").toString().trim() !== "") return;

    var name = (data.get("name") || "").toString().trim();
    var company = (data.get("company") || "").toString().trim();
    var email = (data.get("email") || "").toString().trim();
    var phone = (data.get("phone") || "").toString().trim();
    var message = (data.get("message") || "").toString().trim();
    var body = "Name: " + name + "\nCompany: " + company + "\nEmail: " + email
      + (phone ? "\nPhone: " + phone : "")
      + "\n\n" + message;

    window.location.href = "mailto:info@agentorgs.com?subject="
      + encodeURIComponent("AgentOrgs enquiry from " + name)
      + "&body=" + encodeURIComponent(body);

    banner.hidden = false;
    banner.className = "banner";
    banner.textContent = "Your email app should open with this note addressed to info@agentorgs.com. Send it from there and we’ll reply with a time for a working session.";
  });
})();
