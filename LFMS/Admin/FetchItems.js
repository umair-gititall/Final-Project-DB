// Loading animation
const words = ["Loading.", "Loading..", "Loading..."];
let index = 0;

setInterval(() => {
  const loader = document.getElementById("Loader");
  if (loader) {
    loader.textContent = words[index];
    index = (index + 1) % words.length;
  }
}, 200);


function setupPopup(popup) {
  const slides = popup.querySelectorAll(".mySlides");
  let localIndex = 0;
  let localTimer;

  const showLocalSlide = (index) => {
    slides.forEach((slide) => slide.classList.remove("active"));
    localIndex = (index + slides.length) % slides.length;
    slides[localIndex].classList.add("active");

    clearTimeout(localTimer);
    localTimer = setTimeout(() => showLocalSlide(localIndex + 1), 5000);
  };

  showLocalSlide(localIndex);

  const prevBtn = popup.querySelector(".prev");
  const nextBtn = popup.querySelector(".next");

  if (prevBtn) {
    prevBtn.onclick = () => showLocalSlide(localIndex - 1);
  }

  if (nextBtn) {
    nextBtn.onclick = () => showLocalSlide(localIndex + 1);
  }
}


document.querySelectorAll(".fetch-button").forEach((button) => {
  button.addEventListener("click", () => {
    const popupId = button.getAttribute("data-popupid");
    const popup = document.querySelector(`.apply-popup[data-popupid="${popupId}"]`);

    if (popup) {
      popup.style.display = "flex";
      popup.classList.add("applyPopUp");
      popup.classList.remove("closePopUp");

      setupPopup(popup);
    }
  });
});

document.querySelectorAll(".apply-popup").forEach((popup) => {
  const crossBtn = popup.querySelector(".cross");
  if (crossBtn) {
    crossBtn.addEventListener("click", () => {
      popup.classList.remove("applyPopUp");
      popup.classList.add("closePopUp");
      setTimeout(() => {
        popup.style.display = "none";
      }, 300);
    });
  }
});

async function downloadZip(folderPath) {
    try {
        const zip = new JSZip();
        const response = await fetch(`ListItems.php?folder=${encodeURIComponent(folderPath)}`);

        if (!response.ok) throw new Error("Could not load file list");

        const imageUrls = await response.json();

        if (imageUrls.length === 0) {
            alert("No images found.");
            return;
        }

        for (let url of imageUrls) {
            const res = await fetch(url);
            if (!res.ok) {
                console.warn(`Skipping: ${url}`);
                continue;
            }
            const blob = await res.blob();
            const filename = url.split("/").pop();
            zip.file(filename, blob);
        }

        const content = await zip.generateAsync({ type: "blob" });
        saveAs(content, "download.zip");
    } catch (err) {
        console.error("Download ZIP error:", err);
        alert("Error downloading ZIP. Check console for details.");
    }
}