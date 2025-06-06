  const input = document.getElementById("ImagesInput");
  const previewBox = document.getElementById("ImagesPreview");

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

  const addedFiles = new Set();

  previewBox.addEventListener("click", (e) => {
    if (e.target.classList.contains("remove-btn")) return;
    input.click();
  });

  input.addEventListener("change", () => {
    Array.from(input.files).forEach(file => {
      if (addedFiles.has(file.name)) return;
      addedFiles.add(file.name);

      const ext = file.name.split('.').pop().toLowerCase();
      const icon = iconMap[ext] || 'file.png';

      const container = document.createElement("div");
      container.style.display = "flex";
      container.style.alignItems = "center";
      container.style.marginTop = "2px";
      container.style.gap = "5px";

      const iconImg = document.createElement("img");
      iconImg.src = `../Assets/Icons/Upload.svg`;
      iconImg.style.width = "20px";
      iconImg.style.height = "20px";

      const fileName = document.createElement("span");
      fileName.textContent = file.name;
      fileName.style.flex = "1";
      fileName.style.fontFamily = "cursive";
      fileName.style.fontSize = "smaller"

      const removeBtn = document.createElement("img");
      removeBtn.src = `../Assets/Icons/Delete.svg`;
      removeBtn.className = "remove-btn";
      removeBtn.style.cursor = "pointer";
      removeBtn.style.height = "15px";
      removeBtn.style.width = "15px";
      removeBtn.title = "Remove file";
      removeBtn.onclick = (event) => {
        event.stopPropagation();
        previewBox.removeChild(container);
        addedFiles.delete(file.name);
      };

      container.appendChild(iconImg);
      container.appendChild(fileName);
      container.appendChild(removeBtn);
      previewBox.appendChild(container);
    });

    input.value = "";
  });