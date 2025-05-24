var bg = document.getElementById("moving-background");
var character = document.getElementById("character");
/*var block1 = document.getElementById("block1");*/
var block2 = document.getElementById("block2");
var gameOver = false;
let score = 0;
let scoreInterval;

character.focus();
/*block1.classList.add("animate-block1");*/
block2.classList.add("animate-block2");


function removeAnimation(){
    character.classList.remove("jump");
};

function jump() {
    if (gameOver) return;

    if (!character.classList.contains("jump")) {
        character.classList.add("jump");
        setTimeout(removeAnimation, 400);
    }
}


function isDead (){
    //get coordinates 
    let characterRect = character.getBoundingClientRect();
   /* let block1Rect = block1.getBoundingClientRect();*/
    let block2Rect = block2.getBoundingClientRect();

    let collision = (rect) => {
        const buffer = 20;
        return (
            characterRect.right - buffer > rect.left &&
            characterRect.left + buffer < rect.right &&
            characterRect.bottom - buffer > rect.top &&
            characterRect.top + buffer < rect.bottom
        );
    };

    if (/*collision(block1Rect) ||*/ collision(block2Rect)) {
        gameOver = true;
        clearInterval(scoreInterval);

        /*block1.style.animation = "none";*/
        /*block1.style.display = "none";*/

        block2.classList.remove("animate-block2");
        block2.style.display = "none";

        bg.classList.remove("bg-animation");
        bg.style.background = "url('images/die-screen.jpg') no-repeat center center";

        character.style.display = "none";

        document.getElementById("gameOverMessage").style.display = "block";
        document.getElementById("restartButton").style.display = "block";
    }
}

character.addEventListener("keydown", (event)=>{
    if(event.code === "Space")
        jump();
});

document.addEventListener("click", () => {
    character.focus();
});

setInterval(isDead, 10);


function restartGame() {
    gameOver = false;
    score = 0;
    let fscore = String(score).padStart(4, '0');
    document.getElementById("score").innerText = fscore;
    
    /*block1.classList.add("animate-block1");*/
    /*block1.style.display = "block";*/

    bg.style.background = "url('images/forest.jpg') repeat-x";
    bg.classList.add("bg-animation"); 

    block2.style.display = "block";
    block2.classList.add("animate-block2");

    character.style.display = "block";  
    
    document.getElementById("gameOverMessage").style.display = "none";
    document.getElementById("restartButton").style.display = "none";

    startGame();
}

function startGame() {
    scoreInterval = setInterval(() => {
        score++;
        let fscore = String(score).padStart(4, '0');
        document.getElementById("score").innerText =  fscore;
    }, 500);
}


document.getElementById("restartButton").addEventListener("click", restartGame);

startGame();