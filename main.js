// Sticky menu background
window.addEventListener("scroll", function () {
  if (window.scrollY > 150) {
    this.document.querySelector("#navbar").style.opacity = 0.9;
  } else {
    document.querySelector("#navbar").style.opacity = 1;
    this.document.querySelector("#navbar").style.background = "#fff";
    // box shadow on scroll
    this.document.querySelector("#navbar").style.boxShadow = "0px 0px 7px gray";
  }
});

// Smooth Scrolling
$("#navbar a, btn").on("click", function (event) {
  if (this.hash !== "") {
    event.preventDefault();

    const hash = this.hash;

    $("html, body").animate(
      {
        scrollTop: $(hash).offset().top - 100,
      },
      800
    );
  }
});

// Hamburger Menu Toggle
// const hamburger = document.getElementById("nav__hamburger");
// const navUL = document.getElementById("nav__ul");

// hamburger.addEventListener("click", () => {
//   navUL.classList.toggle("show");
// });

const nav = document.getElementById("nav__slide");
const toggle = document.getElementById("nav__toggle");

toggle.addEventListener("click", () => {
  nav.classList.toggle("active");
});
