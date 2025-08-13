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
  // Images SlideShow
  let slideIndex = 0;

  const slides = document.querySelectorAll(".mySlides");

  let totalitems = 0,
    items = [],
    itemquantity = [0];

  for (let i = 0; i < slides.length; i++) {
    if (items.includes(slides[i].id)) continue;
    items.push(slides[i].id);
    totalitems++;
  }

  for (let i = 0; i < items.length; i++) {
    if (i >= itemquantity.length) itemquantity.push(0);
    for (let j = 0; j < slides.length; j++)
      if (items[i] == slides[j].id) itemquantity[i]++;
  }

  let seperatedslides = new Array(totalitems);

  for (let i = 0, j = 0; i < totalitems; i++) {
    seperatedslides[i] = new Array(itemquantity[i]);
    for (let k = 0; k < itemquantity[i]; j++, k++)
      seperatedslides[i][k] = slides[j];
  }

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
      document.getElementById("global_loading").style.zIndex = 3;
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
          window.location.href = "ClaimItem.php";
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
      let i = 0;
      let test = true;

      for (; i < items.length; i++) if (items[i] == button.id) break;
      seperatedslides[i][0].classList.add("active");

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

        seperatedslides[i][0].classList.remove("active");
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

  document.querySelectorAll(".prev").forEach((btn) => {
    btn.addEventListener("click", function () {
      let i = 0;
      let test = true;

      for (; i < items.length; i++) if (items[i] == btn.id) break;

      seperatedslides[i].forEach((s) => {
        if (s.classList.contains("active")) test = false;
      });

      if (test == true) {
        slides.forEach((slide) => {
          slide.classList.remove("active");
        });
        slideIndex = 0;
      }

      seperatedslides[i][slideIndex].classList.remove("active");
      slideIndex =
        (slideIndex + seperatedslides[i].length - 1) %
        seperatedslides[i].length;
      seperatedslides[i][slideIndex].classList.add("active");
    });
  });

  document.querySelectorAll(".next").forEach((btn) => {
    btn.addEventListener("click", function () {
      let i = 0;
      let test = true;

      for (; i < items.length; i++) if (items[i] == btn.id) break;

      seperatedslides[i].forEach((s) => {
        if (s.classList.contains("active")) test = false;
      });

      if (test == true) {
        slides.forEach((slide) => {
          slide.classList.remove("active");
          console.log(slideIndex);
        });
        slideIndex = 0;
      }

      seperatedslides[i][slideIndex].classList.remove("active");
      slideIndex =
        (slideIndex + seperatedslides[i].length + 1) %
        seperatedslides[i].length;
      seperatedslides[i][slideIndex].classList.add("active");
    });
  });
});
