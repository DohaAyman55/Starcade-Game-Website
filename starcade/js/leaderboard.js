//test sample
const Data = [
    { rank: "S", name: "Alice", score: 1500 },
    { rank: "A", name: "Bob", score: 1400 },
    { rank: "B", name: "Charlie", score: 1300 },
    { rank: "C", name: "David", score: 1200 },
    { rank: "D", name: "Eve", score: 1100 }
];

function addContent() {
    let body = document.getElementById("body");

    //clear table
    body.innerHTML = "";

    //loop on all players' data
    Data.forEach(player => {
    //create row
    const row = document.createElement("tr");

    //add 3 cells
    const rankCell = document.createElement("td");
    const nameCell = document.createElement("td");
    const scoreCell = document.createElement("td");

    //set value
    rankCell.textContent = player.rank;
    nameCell.textContent = player.name;
    scoreCell.textContent = player.score;

    //add cells to row
    row.appendChild(rankCell);
    row.appendChild(nameCell);
    row.appendChild(scoreCell);

    //add row to table
    body.appendChild(row);
    });
}

//call function when page loads
document.addEventListener("DOMContentLoaded", addContent);
