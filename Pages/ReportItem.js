const input = document.getElementById("ImagesInput");
const previewBox = document.getElementById("ImagesPreview");
const form = document.querySelector("form");
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
    const phone = form.querySelector('input[placeholder="Phone"]').value;
    const item = form.querySelector('input[placeholder="Item"]').value;
    const date = form.querySelector('input[type="date"]').value;
    const location = form.querySelector('input[placeholder="Location"]').value;
    const description = document.getElementById("Description").value;

    const formData = new FormData();
    formData.append("name", name);
    formData.append("email", email);
    formData.append("phone", phone);
    formData.append("item", item);
    formData.append("date", date);
    formData.append("location", location);
    formData.append("description", description);

    addedFiles.forEach(file => {
        formData.append("files[]", file);
    });

    fetch("ReportItem.php", {
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



const words = ["Loading.", "Loading..", "Loading..."];
let index = 0;

setInterval(() => {
  document.getElementById("loader").textContent = words[index];
  index = (index + 1) % words.length;
}, 200);