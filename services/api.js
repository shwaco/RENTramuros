// retrieve popular attractions 
export async function getPopularAttractions() {
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
export async function getRecommendedAttractions() {
    try {
        const response = await fetch('/RENTramuros/backend/attractions/retrieve_attractions.php');

        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`)
        } else {
            const result = await response.json();
            return result.data || [];
        }

    } catch (error) {
        console.error(error.message);
        return [];
    }
} 

