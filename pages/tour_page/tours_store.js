// --- MASTER RESERVATION DATA RECORD ---
let reservationData = {
    wantsPackage: null, 
    selectedPackage: null, 
    selectedPackageId: null, 
    selectedPackagePrice: 0,
    selectedVehicle: null,
    selectedVehicleId: null, 
    selectedVehiclePrice: 0,
    selectedPackageDesc: "", 
    selectedPackageItineraryIds: [], 
    attractionDictionary: {},       
    attractionFees: {}, 
    vehicleQuantity: 0,
    customAttractions: [], 
    customAttractionIds: [],
    tourists: {
        adults: 2,
        children: 0,
        infants: 0
    },
    includesSeniors: false,
    
    contactInfo: {
        firstName: "",
        lastName: "",
        email: "",
        phone: ""
    }
};
