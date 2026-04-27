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

// tours step 2 

export async function fetchToursData() {
    try {
        // Since the JSON is in the same folder as the PHP file you are viewing, 
        // the path is just the filename!
        const response = await fetch('tours_data_step2.json');
        
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        
        const data = await response.json();
        return data;
    } catch (error) {
        console.error("Failed to fetch tours data:", error);
        return null;
    }
}