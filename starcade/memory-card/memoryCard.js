
/* selecting 2 cards only */
    /* 
    if 2 cards are flipped, compare them
s    if they don't amtch, flip them back to front
    */
   

/* comparing 2 cards */
    /* 
    third pic for a card that has found its match
    initially flipped 270 degrees and hidden 
    
    if match found (pic1 = pic2) >> flip 3rd pic 90 degrees to show it
    if not >> flip back to front 
    */

/*  */


let flippedCards = [];
let lockBoard = false;
let score = 0;

function checkWinCondition() {
    const allCards = document.querySelectorAll('.card');
    const matchedCards = document.querySelectorAll('.card.matched');
    if (matchedCards.length === allCards.length) {
        document.getElementById('win-message').style.display = 'block';
    }
}

function updateScoreDisplay() {
    document.getElementById('score').innerText = `${score}`.padStart(3, '0');
}

function handleCardClick(e) {
    const card = e.currentTarget;

    if (lockBoard || card.classList.contains("flip") || card.classList.contains("matched")) {
        return;
    }

    card.classList.add("flip");
    flippedCards.push(card);

    if (flippedCards.length === 2) {
        checkForMatch();
    }
}

function checkForMatch() {
    const [card1, card2] = flippedCards;
    const isMatch = card1.dataset.name === card2.dataset.name;

    if (isMatch) {
        card1.classList.add("matched");
        card2.classList.add("matched");
        flippedCards = [];
        score += 100;
        updateScoreDisplay();
        checkWinCondition();
    } else {
        lockBoard = true;
        setTimeout(() => {
            card1.classList.remove("flip");
            card2.classList.remove("flip");
            flippedCards = [];
            score -= 20;
            updateScoreDisplay();
            lockBoard = false;
        }, 1000);
    }
}
shuffleCards(); // Shuffle the cards before game starts
// Initially show all cards for 3 seconds, then hide them
document.querySelectorAll(".card").forEach(card => {
    card.classList.add("flip");
});

// After 3 seconds, flip all cards back to front
setTimeout(() => {
    document.querySelectorAll(".card").forEach(card => {
        card.classList.remove("flip");
    });
}, 3000);


// Add event listeners
document.querySelectorAll(".card").forEach((card) => {
    card.addEventListener("click", handleCardClick);
});


function shuffleCards() {
    const gameBoard = document.querySelector(".GameBoard");
    const cards = Array.from(gameBoard.children);

    // Shuffle array using Fisher-Yates algorithm
    for (let i = cards.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [cards[i], cards[j]] = [cards[j], cards[i]];
    }

    // Append shuffled cards back to the GameBoard
    cards.forEach(card => gameBoard.appendChild(card));
}
