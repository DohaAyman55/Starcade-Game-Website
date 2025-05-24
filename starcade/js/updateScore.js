
function checkScore(){
    //get iframe element
    let iframe = document.getElementById("game");

    //get html inside the iframe    
    let iframeDoc = iframe.contentDocument;     

    //get the score element from the html retreived
    let score = iframeDoc.getElementById("score");

    //get the value inside the score element and parse int?
    let current_score = parseInt(score.textContent);

    //send current  score to database
    fetch('../php/updateScore.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ score: current_score })
    })
    .then(response => response.text())
    .then(data => {
        console.log('Server Response:', data);
    })
    .catch(error => {
        console.error('Error:', error);
    });
};

setInterval(checkScore, 500);