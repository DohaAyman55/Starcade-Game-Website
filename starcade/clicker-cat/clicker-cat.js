let clicks = 0;
let cat = document.getElementById("cat");

function incrementClicks() {
    clicks++;
    let numOfClicks = String(clicks).padStart(4, '0');
    document.getElementById("score").innerText = numOfClicks;
}

function resetCat() {
    cat.src = "close.jpg";
}

function changeCat() {
    cat.src = "open.jpg";
}

cat.addEventListener("click", function() {
    incrementClicks();
    changeCat();
    setTimeout(resetCat, 200);
});