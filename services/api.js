// retrieve popular attractions 
async function getPopularAttractions() {
    try {
        const response = await fetch('/RENTramuros/backend/attractions/retrieve_attractions.php');

        if (!response.ok) {
            throw new Error (`Response status: ${response.status}`);
        } else {
            const result = await response.json();
            return result.data || [];
        } 

    } catch (error) {
        console.error(error.message);
        return [];
    } 
}

// retrieve recommended attractions
async function getRecommendedAttractions() {
    try {
        const response = await fetch('/RENTramuros/backend/attractions/PHPFILE');

        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`)
        } else {
            const result = response.json();
            return result.data || [];
        }

    } catch (error) {
        console.error(error.message);
        return [];
    }
} 

document.addEventListener('DOMContentLoaded', getPopularAttractions);