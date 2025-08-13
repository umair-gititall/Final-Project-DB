const words = ["Loading.", "Loading..", "Loading..."];
let index = 0;

setInterval(() => {
  document.getElementById("Loader").textContent = words[index];
  index = (index + 1) % words.length;
}, 200);


window.addEventListener("DOMContentLoaded", () => {
  const applyButtons = document.querySelectorAll(".apply-btn");

  applyButtons.forEach(button => {
    button.addEventListener("click", () => {
      const popupId = button.getAttribute("data-popup-id");
      const applypopup = document.getElementById(popupId);
      const cross = applypopup.querySelector(".Cross");

      applypopup.classList.remove("closePopUp");
      applypopup.style.display = "flex";
      applypopup.classList.add("applyPopUp");

      // Close button inside the popup
      cross.addEventListener("click", () => {
        applypopup.classList.remove("applyPopUp");
        applypopup.classList.add("closePopUp");

        setTimeout(() => {
          applypopup.style.display = "none";
        }, 800);
      });
    });
  });
});


//Images SlideShow
let slideIndex = 0;
const slides = document.querySelectorAll(".mySlides");
const dots = document.querySelectorAll(".dot");
let timer;

function showSlide(index) {
    slides.forEach((slide, i) => {
        slide.classList.remove("active");
    });

    slideIndex = (index + slides.length) % slides.length;

    slides[slideIndex].classList.add("active");
  console.log(slides.length);
    clearTimeout(timer);
    timer = setTimeout(() => showSlide(slideIndex + 1), 5000);
}

function changeSlide(n) {
    showSlide(slideIndex + n);
}

showSlide(slideIndex);