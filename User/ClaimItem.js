const iconMap = {
  pdf: "pdf.png",
  doc: "word.png",
  docx: "word.png",
  png: "image.png",
  jpg: "image.png",
  jpeg: "image.png",
  gif: "image.png",
  mp4: "video.png",
  mov: "video.png",
  avi: "video.png",
};

window.addEventListener("DOMContentLoaded", () => {
  const forms = document.querySelectorAll("form[id^='AnswerForm_']");

  forms.forEach((form) => {
    const input = form.querySelector("#ImagesInput");
    const previewBox = form.querySelector(".ImagesPreview");
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
      Array.from(input.files).forEach((file) => {
        if (addedFiles.has(file.name)) return;

        addedFiles.set(file.name, file);

        const ext = file.name.split(".").pop().toLowerCase();
        const icon = iconMap[ext] || "file.png";

        const fileBox = document.createElement("div");
        fileBox.classList.add("file-box");
        fileBox.innerHTML = `
                    <img src="../Assets/Icons/Upload.svg" alt="${ext}" width="20" height="20">
                    <span>${file.name}</span>
                    <img src="../Assets/Icons/Delete.svg" class="remove-btn" data-filename="${file.name}" title="Remove" style="width:18px; margin-left:8px; cursor:pointer;">
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

      addedFiles.forEach((file) => {
        formData.append("files[]", file);
      });

      fetch("ClaimItem2.php", {
        method: "POST",
        body: formData,
      })
        .then((res) => res.text())
        .then((data) => {
          alert(data);
          window.location.href = "../index.html";
        })
        .catch((err) => {
          console.error("Upload failed", err);
          alert("Failed to submit. Please try again.");
        });
    });

    const uploadIcon = document.createElement("img");
    uploadIcon.src = "../Assets/Icons/Upload.svg";
    uploadIcon.alt = "Upload";
    uploadIcon.id = "uploadIcon";
    uploadIcon.style = "width: 32px; cursor: pointer;";
    previewBox.appendChild(uploadIcon);
  });

  const applyButtons = document.querySelectorAll(".apply-btn");

  applyButtons.forEach((button) => {
    button.addEventListener("click", () => {
      const popupId = button.getAttribute("data-popup-id");
      const applypopup = document.getElementById(popupId);
      const cross = applypopup.querySelector(".Cross");

      applypopup.classList.remove("closePopUp");
      applypopup.style.display = "flex";
      applypopup.classList.add("applyPopUp");

      cross.addEventListener("click", () => {
        applypopup.classList.remove("applyPopUp");
        applypopup.classList.add("closePopUp");

        setTimeout(() => {
          applypopup.style.display = "none";
        }, 800);
      });
    });
  });

  // Loader animation
  const words = ["Loading.", "Loading..", "Loading..."];
  let index = 0;

  setInterval(() => {
    document.getElementById("Loader").textContent = words[index];
    index = (index + 1) % words.length;
  }, 200);

  // Images SlideShow
  let slideIndex = 0;
  const slides = document.querySelectorAll(".mySlides"); // List of slides
  const dots = document.querySelectorAll(".dot");
  let timer;

  const arr = [];
  function showSlide(index) {
    // Get data-val from the element with ID "testSlide"
    let max = 0;
    // Hide all slides
    let rows;
    slides.forEach((slide) => {
      slide.classList.remove("active");
      arr[max] = slide.dataset.val;
      max++;
      rows = slide.dataset.val;
    });
  }
  console.log(arr[0]);
  slideIndex = (index + arr[index]) % arr[index];
  //   if (rows != 1) slideIndex++;
  slides[slideIndex].classList.add("active");

  window.changeSlide = function (n) {
    showSlide(slideIndex + n);
  };

  showSlide(slideIndex);
});
