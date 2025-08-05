const input = document.getElementById("ImagesInput");
const previewBox = document.getElementById("ImagesPreview");
const form = document.querySelector("form");
const reportBtn = document.getElementById("button");

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

    const fileBox = document.createElement("div");
    fileBox.classList.add("file-box");
    fileBox.innerHTML = `
            <img src="../Assets/Icons/Upload.svg" alt="${ext}" width="30">
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
  const phone = form.querySelector('input[name="phone"]').value;
  const ListBox = document.getElementById("ListBox").value;
  const item = form.querySelector('input[placeholder="Item"]').value;
  const date = form.querySelector('input[type="date"]').value;
  const location = form.querySelector('input[placeholder="Location"]').value;
  const description = document.getElementById("Description").value;

  const formData = new FormData();
  formData.append("name", name);
  formData.append("email", email);
  formData.append("phone", phone);
  formData.append("listbox", ListBox);
  formData.append("item", item);
  formData.append("date", date);
  formData.append("location", location);
  formData.append("description", description);

  addedFiles.forEach((file) => {
    formData.append("files[]", file);
  });

  fetch("ReportItem.php", {
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

window.addEventListener("DOMContentLoaded", () => {
  const uploadIcon = document.createElement("img");
  uploadIcon.src = "../Assets/Icons/Upload.svg";
  uploadIcon.alt = "Upload";
  uploadIcon.id = "uploadIcon";
  uploadIcon.style = "width: 32px; cursor: pointer;";
  previewBox.appendChild(uploadIcon);
});

//loading animation
const words = ["Loading.", "Loading..", "Loading..."];
let index = 0;

setInterval(() => {
  document.getElementById("loader").textContent = words[index];
  index = (index + 1) % words.length;
}, 200);

//Date Restriction
var today_max = new Date();
var today_min = new Date();
var dd = today_max.getDate();
var mm = today_max.getMonth() + 1;
var yyyy = today_max.getFullYear();

if (dd < 10) {
  dd = "0" + dd;
}

if (mm < 10) {
  mm = "0" + mm;
}

today_max = yyyy + "-" + mm + "-" + dd;
today_min = yyyy - 1 + "-" + mm + "-" + dd;
document.getElementById("date").setAttribute("max", today_max);
document.getElementById("date").setAttribute("min", today_min);

const ListBox = document.getElementById("ListBox");
const phoneInfo = document.getElementById("phone");

ListBox.addEventListener("change", function () {
  const selected = ListBox.value;
  if (selected == "+92") {
    document
      .getElementById("phone")
      .setAttribute("placeholder", "[3/4]xxxxxxxx");
    document.getElementById("phone").setAttribute("pattern", "[3-4][0-9]{9}");
  } else if (selected == "+91") {
    document
      .getElementById("phone")
      .setAttribute("placeholder", "[7-9]xxxxxxxxx");
    document.getElementById("phone").setAttribute("pattern", "[7-9][0-9]{9}");
  } else if (selected == "+1") {
    document
      .getElementById("phone")
      .setAttribute("placeholder", "[AAA]BBB-CCCC");
    document.getElementById("phone").setAttribute("pattern", "[2-9][0-9]{9}");
  }
});

function checkWordLimit(textarea, limit) {
  const words = textarea.value.trim().split(/\s+/);
  const wordCount = words.filter((word) => word.length > 0).length;

  const msg = document.getElementById("wordCountMsg");

  if (wordCount > limit) {
    msg.textContent = `Too long! Max ${limit} words allowed`;
    textarea.value = words.slice(0, limit).join(" ");
  } else {
    msg.textContent = `${wordCount}/${limit} words`;
  }
}
