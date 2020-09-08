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

const nav = document.getElementById("nav__slide");
const toggle = document.getElementById("nav__toggle");

toggle.addEventListener("click", () => {
  nav.classList.toggle("active");
});
