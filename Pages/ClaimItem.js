const words = ["Loading.", "Loading..", "Loading..."];
let index = 0;

setInterval(() => {
  document.getElementById("loader").textContent = words[index];
  index = (index + 1) % words.length;
}, 200);
