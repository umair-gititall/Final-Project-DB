const input = document.getElementById("ImagesInput");
const previewBox = document.getElementById("ImagesPreview");
const form = document.getElementById("AnswerForm");
const reportBtn = document.getElementById("button");

const iconMap = {
    'pdf': 'pdf.png',
    'doc': 'word.png',
    'docx': 'word.png',
    'png': 'image.png',
    'jpg': 'image.png',
    'jpeg': 'image.png',
    'gif': 'image.png',
    'mp4': 'video.png',
    'mov': 'video.png',
    'avi': 'video.png'
};

const addedFiles = new Map();

previewBox.addEventListener("click", (e) => {
    if (e.target.classList.contains("remove-btn")) {
        const fileName = e.target.dataset.filename;
        const fileBox = e.target.parentElement;
        addedFiles.delete(fileName);
        fileBox.remove();
        return;
    }

    if (e.target.id === "uploadIcon" || e.target.closest("#uploadIcon")) {
        input.click();
    }
});

input.addEventListener("change", () => {
    Array.from(input.files).forEach(file => {
        if (addedFiles.has(file.name)) return;

        addedFiles.set(file.name, file);

        const ext = file.name.split('.').pop().toLowerCase();
        const icon = iconMap[ext] || 'file.png';

        const fileBox = document.createElement("div");
        fileBox.classList.add("file-box");
        fileBox.innerHTML = `
            <img src="../Assets/Icons/${icon}" alt="${ext}" width="30">
            <span>${file.name}</span>
            <img src="../Assets/Icons/trash.png" class="remove-btn" data-filename="${file.name}" title="Remove" style="width:18px; margin-left:8px; cursor:pointer;">
        `;
        previewBox.appendChild(fileBox);
    });
});

form.addEventListener("submit", function (e) {
    e.preventDefault();

    const name = form.querySelector('input[placeholder="Name"]').value;
    const email = form.querySelector('input[placeholder="Email"]').value;
    const phone = form.querySelector('input[name="PhoneNo"]').value;
    const answer = form.querySelector('input[name="Answer"]').value;
    const itemid = form.querySelector('input[name="ItemID"]').value;

    const formData = new FormData();
    formData.append("Name", name);
    formData.append("Email", email);
    formData.append("PhoneNo", phone);
    formData.append("Answer", answer);
    formData.append("ItemID", itemid);

    addedFiles.forEach(file => {
        formData.append("files[]", file);
    });

    fetch("ClaimItem2.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(data => {
        alert(data);
        window.location.href = "../index.html";
    })
    .catch(err => {
        console.error("Upload failed", err);
        alert("Failed to submit. Please try again.");
    });
});

window.addEventListener("DOMContentLoaded", () => {
    const uploadIcon = document.createElement("img");
    uploadIcon.src = "../Assets/Icons/upload.png";
    uploadIcon.alt = "Upload";
    uploadIcon.id = "uploadIcon";
    uploadIcon.style = "width: 32px; cursor: pointer;";
    previewBox.appendChild(uploadIcon);
});


// Apply popup logic after DOM is loaded
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

const words = ["Loading.", "Loading..", "Loading..."];
let index = 0;

setInterval(() => {
  document.getElementById("Loader").textContent = words[index];
  index = (index + 1) % words.length;
}, 200);



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