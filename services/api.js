// retrieve recommended attractions 
async function getRecommendedAttractions() {
    try {
        const response = await fetch('/RENTramuros/backend/attractions/retrieve_attractions.php');

        if (!response.ok) {
            throw new Error (`Response status: ${response.status}`);
        }

        const result = await response.json();
        console.log(result);

    } catch (error) {
        console.error(error.message);
    } 
}

document.addEventListener('DOMContentLoaded', getRecommendedAttractions);