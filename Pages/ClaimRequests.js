const words = ["Loading.", "Loading..", "Loading..."];
let index = 0;

setInterval(() => {
  document.getElementById("Loader").textContent = words[index];
  index = (index + 1) % words.length;
}, 200);


const button = document.getElementById("Button");
const applypopup = document.getElementById("ApplyPopUp");
const cross = document.getElementById("Cross");

button.onclick = (event) =>
{
  applypopup.classList.remove("closePopUp");
  applypopup.style.display = "flex";
  applypopup.classList.add("applyPopUp");
}


cross.onclick = (event) =>
{
  applypopup.classList.remove("applyPopUp");
  applypopup.classList.add("closePopUp");
}