export default class Env {
    constructor() {
        this.value
    }
    

    getEnv(apiID) {
    return new Promise((resolve, reject) => {
        $.ajax({
            method: "POST",
            url: "app.php",
            dataType: "JSON", // Expect JSON response from server
            data: {
                getenv: "getenv",
                type: apiID
            },
            success: function (data) {
                console.log(data);
                if (data.hasOwnProperty("key")) {
                    resolve(data.key); // Resolve the promise with the key
                } else if (data.hasOwnProperty("error")) {
                    console.error(data.error); // Log error message
                    reject(data.error);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", error); // Log AJAX error
                console.error("AJAX Status:", status); // Log AJAX error
                console.error("AJAX xhr:", xhr); // Log AJAX error
                
                reject(error);
            }
        });
    });
}

// Usage
    async fetchApiKey(type) {
    console.log(type)
    try {
        const apiKey = await this.getEnv(type);
        return (apiKey);
    } catch (error) {
        console.log("Failed to retrieve API key:", error);
    }
}

}