//Gets the divs where the language and dining options are in
const languageOption = document.getElementById("languageOptions");
const diningOption = document.getElementById("diningOptions");

//This function is triggered by the first click on the page and shows the language options whilst hiding the dining options
function startingClick() {
    languageOption.style.display = "flex";
    diningOption.style.display = "none";

    //adds fade out animation for background image
    document.querySelector("main").classList.add("fade-out");

    //Delet the click listener so it only happens once
    document.removeEventListener("click", startingClick);
}

//event listener for said first click
document.addEventListener("click", startingClick);


const diningTranslations = {
    English: {
        dine_in: "Dine In",
        take_out: "Take Out",
    },
    Dutch: {
        dine_in: "Hier eten",
        take_out: "Meenemen",
    }
};

function updateDiningLabels(language) {
    const set = diningTranslations[language] || diningTranslations.English;
    document.querySelectorAll(".diningLabel").forEach(label => {
        const key = label.dataset.key;
        if (set[key]) {
            label.textContent = set[key];
        }
    });
}

//Loops through all the language options
document.querySelectorAll(".languageOption").forEach(option => {
    //Adds a click event to each language option
    option.addEventListener("click", () => {

        //gets the language from the data-language thing
        const language = option.dataset.language;

        // saves the chosen language to session 
        saveToSession("languageOption", language, () => {
            updateDiningLabels(language);
        });

        languageOption.style.display = "none";
        diningOption.style.display = "flex";
    });
});


//Loops through all the dining options
document.querySelectorAll(".diningOption").forEach(option => {
    //Adds a click event to each dining option
    option.addEventListener("click", () => {

        //gets the dining option from the data-dining thing
        const dining = option.dataset.dining;

        // saves the chosen language to session 
        //after which it redirects user to menu.php
        saveToSession("diningOption", dining, () => {
            window.location.href = "menu.php";
        });
    });
});


//This function send data to server to save in the php session
function saveToSession(key, value, callback) {
    fetch("saveSession.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        //Sends the key and value as JSON to php
        body: JSON.stringify({ key, value })
    }).then(() => {
        //If a callback is given, call it after data is saved
        if (callback) callback();
    });
}
