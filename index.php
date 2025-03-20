
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<style type="text/css">
    body {
    font-family: sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
    background-color: #f4f4f4;
}

.container {
    width: 600px;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.search-bar {
    display: flex;
    margin-bottom: 20px;
}

.search-bar input {
    flex-grow: 1;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px 0 0 4px;
}

.search-bar button {
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 0 4px 4px 0;
    cursor: pointer;
}

#weatherData, #forecastData{
    margin-top: 20px;
}
</style>
<body>
    <div class="container">
        <h1>Weather Dashboard</h1>
        <div class="search-bar">
            <input type="text" id="cityInput" placeholder="Enter city name">
            <button id="searchButton">Search</button>
        </div>
        <div id="weatherData">
            </div>
        <div id="forecastData">
            </div>
    </div>
    <script src="script.js"></script>
</body>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
    const cityInput = document.getElementById('cityInput');
    const searchButton = document.getElementById('searchButton');
    const weatherData = document.getElementById('weatherData');
    const forecastData = document.getElementById('forecastData');
    const apiKey = '4ab82c0758415146d0192fb9f73e902b';

    searchButton.addEventListener('click', function() {
        const city = cityInput.value.trim(); // Trim whitespace
        if (city) {
            fetchWeatherData(city);
            fetchForecastData(city);
        }
    });

    function fetchWeatherData(city) {
        const url = `https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${apiKey}&units=metric`;
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.cod && data.cod !== 200) {
                    throw new Error(data.message);
                }
                displayWeatherData(data);
            })
            .catch(error => {
                weatherData.innerHTML = `<p>Error fetching weather data: ${error.message}</p>`;
            });
    }

    function fetchForecastData(city) {
        const url = `https://api.openweathermap.org/data/2.5/forecast?q=${city}&appid=${apiKey}&units=metric`;
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.cod && data.cod !== 200) {
                    throw new Error(data.message);
                }
                displayForecastData(data.list);
            })
            .catch(error => {
                forecastData.innerHTML = `<p>Error fetching forecast data: ${error.message}</p>`;
            });
    }
    function displayWeatherData(data) {
        weatherData.innerHTML = `
            <h2>${data.name}, ${data.sys.country}</h2>
            <p>Temperature: ${data.main.temp}°C</p>
            <p>Description: ${data.weather[0].description}</p>
            <p>Humidity: ${data.main.humidity}%</p>
        `;
    }
    function displayForecastData(forecastList) {
        let forecastHtml = '<h2>5-Day Forecast</h2>';
        for(let i = 0; i < forecastList.length; i+=8){
            const forecast = forecastList[i];
            const date = new Date(forecast.dt * 1000);
            forecastHtml += `
                <div>
                    <h3>${date.toLocaleDateString()}</h3>
                    <p>Temperature: ${forecast.main.temp}°C</p>
                    <p>Description: ${forecast.weather[0].description}</p>
                </div>
            `;
        }
        forecastData.innerHTML = forecastHtml;
    }
});
</script>
</html>
