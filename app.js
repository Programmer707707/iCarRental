const carDataUrl = "filter.php";

document.addEventListener("DOMContentLoaded", () => {
    const carList = document.getElementById("carList");
    const filterForm = document.getElementById("filterForm");

    const loadCars = (filters = {}) => {
        const query = new URLSearchParams(filters).toString();

        fetch(`${carDataUrl}?${query}`)
            .then(response => response.json())
            .then(data => {
                carList.innerHTML = data.map(car => `
                    <div class="car-card">
                        <img src="${car.image}" alt="${car.model}">
                        <h3>${car.brand} ${car.model}</h3>
                        <p>${car.passengers} seats - ${car.transmission.toLowerCase()}</p>
                        <p>${car.daily_price_huf} Ft</p>
                        <a href="carDetails.php?car_id=${car.id}" class="btn">View Details</a>
                    </div>
                `).join("");
            });
    };

    // filterForm.addEventListener("submit", event => {
    //     event.preventDefault();

    //     const formData = new FormData(filterForm);
    //     const filters = {
    //         seats: formData.get("seats"),
    //         transmission: formData.get("transmission"),
    //         priceMin: formData.get("priceMin"),
    //         priceMax: formData.get("priceMax"),
    //     };

    //     loadCars(filters);
    // });



    filterForm.addEventListener("submit", event => {
        event.preventDefault();

        const formData = new FormData(filterForm);
        const filters = Object.fromEntries(formData.entries());
        loadCars(filters);
    });


    loadCars();
});
