document.addEventListener('DOMContentLoaded', function() {
    const currentId = localStorage.getItem('currentId') ? parseInt(localStorage.getItem('currentId'), 10) : 1;
    var days = ['M', 'T', 'W', 'Th', 'F', 'Sa', 'Su'];
    var apiContent = false;
    weeklyMP();
    changeIngredients(0);
    console.log(apiContent)

    document.getElementById("saveMpBtn").addEventListener('click', function () {
        if (this.getAttribute('data-saved') === "notsaved") {
            this.innerHTML = 'Meal Plan Saved';
            this.setAttribute('data-saved', 'saved');
            saveMP('saved');
        } else {
            this.innerHTML = 'Save Meal Plan'; // Plus sign
            this.setAttribute('data-saved', 'notsaved');
            saveMP('notsaved');
        }
    });


    function saveMP(saved) {
        var xhrSaveMP = new XMLHttpRequest();
        xhrSaveMP.open("GET", `https://cgi.luddy.indiana.edu/~team30/saved-content/save-meal-plan.php?saved=${saved}`, true);

        xhrSaveMP.onload = function () {
            if (xhrSaveMP.status >= 200 && xhrSaveMP.status < 300) {

            } else {
                // Handle request errors here
                console.error('Error:', xhrSaveMP.statusText);
            }
        };
        xhrSaveMP.send();
    }
    
    //Show meal plan
    document.getElementById('showMpBtn').addEventListener('click', function(){
        var mealplan = document.getElementById('weekly-wrapper');
        mealplan.style.display = 'flex';

        var mpBtn = document.getElementById('showMpBtn');
        mpBtn.style.display = "none";

        var backBtn = document.getElementById('contentBtn');
        backBtn.style.display = "flex";

        var videoContainer = document.getElementById('scroll-video-like-wrapper');
        videoContainer.style.display = 'none';

        var ingr = document.getElementById('ingredients-wrapper');
        ingr.style.display = 'none';

        document.getElementById('contentBtn').addEventListener('click', function() {
            videoContainer.style.display = 'flex';
            ingr.style.display = 'flex';
            mpBtn.style.display = 'flex';
            backBtn.style.display = "none";
            mealplan.style.display = 'none';
        });
    });

    //Load current days in meal plan as green
    function removeDayOption() {
        var xhrLoadDays = new XMLHttpRequest();
        xhrLoadDays.open("GET", `https://cgi.luddy.indiana.edu/~team30/saved-content/on-load.php`, true);

        xhrLoadDays.onload = function () {
            if (xhrLoadDays.status >= 200 && xhrLoadDays.status < 300) {
                var data = JSON.parse(xhrLoadDays.responseText);
                
                data.arrayOfDays.forEach(function(day) {
                    var dow = document.getElementById(`${day}-day-btn`);
                    if (dow) {
                        dow.className = 'dow-button-selected';
                    }

                    var index = days.findIndex(function(element) {
                        return element === day;
                    });
                    
                    if (index !== -1) {
                        days.splice(index, 1);
                    }
                });

            } else {
                    // Handle request errors here
                    console.error('Error:', xhrLoadDays.statusText);
            }
        };
        xhrLoadDays.send();
    }
    removeDayOption();

    document.getElementById('savedBtn').addEventListener('click', function() {
        var currentId = document.getElementById('videoContainer').getAttribute('datacurrentid');
        window.location.href = 'https://cgi.luddy.indiana.edu/~team30/saved.php';
        localStorage.setItem('currentId', currentId);
    });

    document.getElementById('addButton').addEventListener('click', function() {
        const container = document.getElementById('daysContainer'); // The grey container's id should be 'daysContainer'
        const add = document.getElementById('addButton');
        // Clear existing buttons
        //container.innerHTML = '';

        // Create and append buttons for each day
        if (container.hasChildNodes()) { 
            add.textContent = 'ADD RECIPE';
            container.innerHTML = '';
        } else {
            add.textContent = '';
            days.forEach(day => {
                let button = document.createElement('button');
                button.textContent = day;
                button.className = 'day-button'; // Assign a class for styling
                button.addEventListener('click', function() {
                    var dow = document.getElementById(`${day}-day-btn`);
                    if (dow) {
                        dow.className = 'dow-button-selected';
                    }

                    var index = days.findIndex(function(element) {
                        return element === day;
                    });
                    
                    if (index !== -1) {
                        days.splice(index, 1);
                    }

                    var currentId = document.getElementById('videoContainer').getAttribute('datacurrentid');
                    var dow = document.getElementById(`${day}-day-btn`);
                    dow.className = 'dow-button-selected';
                    var apiId = document.getElementById('videoContainer').getAttribute('datacurrentidAPI');
                    
                    //Send information recieved from meal-plan to saved page
                    var xhrFive = new XMLHttpRequest();
                    xhrFive.open("GET", `https://cgi.luddy.indiana.edu/~team30/saved-content/meal-plan.php?currentId=${currentId}&day=${day}&api=${apiContent}&apiId=${apiId}`, true);

                    xhrFive.onload = function () {
                        if (xhrFive.status >= 200 && xhrFive.status < 300) {
                            //Update saved page with recipies 
                            window.location.href = 'https://cgi.luddy.indiana.edu/~team30/for-you.php';
                            

                        } else {
                                // Handle request errors here
                                console.error('Error:', xhrFive.statusText);
                        }
                    };
                    xhrFive.send();
                    container.innerHTML = '';
                    add.textContent = '+';
                });
                container.appendChild(button);
            });
        }
        
    });

    document.getElementById('nextVideo').addEventListener('click', function() {
        var currentId = document.getElementById('videoContainer').getAttribute('datacurrentid');
        if (apiContent) {
            fetchVideo('next', currentId, '');
            apiContent = false;
        } else {
            fetchVideo('next', currentId, 'api');
            apiContent = true;
        }
    });

    document.getElementById('prevVideo').addEventListener('click', function() {
        var currentId = document.getElementById('videoContainer').getAttribute('datacurrentid');
        if (apiContent) {
            fetchVideo('prev', currentId, '');
            apiContent = true;
        } else {
            fetchVideo('prev', currentId, 'api');
            apiContent = false;
        }
    });

    function weeklyMP(){
        var xhrSaved = new XMLHttpRequest();
        xhrSaved.open("GET", `saved-content/get-saved.php`, true);

            xhrSaved.onload = function () {
                if (xhrSaved.status >= 200 && xhrSaved.status < 300) {
                    var dataSaved = JSON.parse(xhrSaved.responseText);

                    const ingredientSaved = document.getElementById('ingredients-list-saved');
                    const steps = document.getElementById('title-saved');
                    const videoSaved = document.getElementById('videoPlayerSaved');
                    const title = document.getElementById('weekly-title-item');

                    var titleConent = document.createElement('h2');
                    titleConent.textContent = 'This Week\'s Meal Plan';
                    titleConent.classList.add('weekly-mp-title');

                    title.appendChild(titleConent);
                    
                    
                    console.log("onLoad");

                    dataSaved.arrayOfDays.user.forEach(function(currentDay) {

                        console.log(currentDay);

                        // Create the main container div
                        const dailyRecipeDiv = document.createElement('div');
                        dailyRecipeDiv.classList.add('daily-recipe');

                        // Create the first item with day name and button
                        const dailyRecipeItem1 = document.createElement('div');
                        dailyRecipeItem1.classList.add('daily-recipe-item-day');

                        const dayNameH1 = document.createElement('h1');
                        dayNameH1.classList.add('day-name');
                        dayNameH1.textContent = currentDay.dayFull;

                        const removeButton = document.createElement('a');
                        removeButton.classList.add('rounded-button');
                        removeButton.id = 'removeBtn';
                        removeButton.textContent = 'Remove';

                        removeButton.addEventListener('click', function() {
                            var xhrRemove = new XMLHttpRequest();
                            xhrRemove.open("GET", `https://cgi.luddy.indiana.edu/~team30/saved-content/remove-day.php?currentId=${currentDay.id}&day=${currentDay.day}`, true);

                            xhrRemove.onload = function () {
                                if (xhrRemove.status >= 200 && xhrRemove.status < 300) {
                                    window.location.href = 'https://cgi.luddy.indiana.edu/~team30/for-you.php';
                                } else {
                                        // Handle request errors here
                                        console.error('Error:', xhrRemove.statusText);
                                }
                            };
                            xhrRemove.send();
                        });

                        dailyRecipeItem1.appendChild(dayNameH1);
                        dailyRecipeItem1.appendChild(removeButton);

                        // Create the second item with video
                        const dailyRecipeItem2 = document.createElement('div');
                        dailyRecipeItem2.classList.add('daily-recipe-item');

                        const videoContainer = document.createElement('div');
                        videoContainer.classList.add('s-video-container');

                        const video = document.createElement('video');
                        video.controls = true;
                        video.classList.add('rounded-video');

                        const source = document.createElement('source');
                        source.id = 'videoPlayerSaved';
                        source.src = 'content/' + currentDay.url;
                        source.type = 'video/mp4';

                        video.appendChild(source);
                        videoContainer.appendChild(video);
                        dailyRecipeItem2.appendChild(videoContainer);

                        // Create the third item with recipe title and ingredients list
                        const dailyRecipeItem3 = document.createElement('div');
                        dailyRecipeItem3.classList.add('daily-recipe-item');

                        const titleSavedH1 = document.createElement('h1');
                        titleSavedH1.classList.add('s-recipe');
                        titleSavedH1.textContent = currentDay.title;

                        const ingredientsTitleH1 = document.createElement('strong');
                        ingredientsTitleH1.classList.add('s-ingredients-title');
                        ingredientsTitleH1.textContent = 'Ingredients';

                        const ingredientsListUl = document.createElement('ul');
                        ingredientsListUl.id = 'ingredients-list-saved';
                        ingredientsListUl.classList.add('s-ingredients-list');

                        dailyRecipeItem3.appendChild(titleSavedH1);
                        dailyRecipeItem3.appendChild(ingredientsTitleH1);
                        dailyRecipeItem3.appendChild(ingredientsListUl);

                        currentDay.ingrarray.forEach(function(element) {
                            const newIngredient = document.createElement('li');
                            newIngredient.textContent = element;
                            ingredientsListUl.appendChild(newIngredient);
                        });

                        // Append all items to the main container
                        dailyRecipeDiv.appendChild(dailyRecipeItem1);
                        dailyRecipeDiv.appendChild(dailyRecipeItem2);
                        dailyRecipeDiv.appendChild(dailyRecipeItem3);

                        // Finally, append the main container to the body or a specific element in your page
                        const weeklyWrapper = document.getElementById('weeklyMpWrapperFY');
                        weeklyWrapper.appendChild(dailyRecipeDiv);
                        // Or use document.getElementById('someElementId').appendChild(dailyRecipeDiv); to append it to a specific element

                    });
                    
                    dataSaved.arrayOfDays.api.forEach(function(currentDay) {

                        console.log(currentDay);

                        // Create the main container div
                        const dailyRecipeDiv = document.createElement('div');
                        dailyRecipeDiv.classList.add('daily-recipe');

                        // Create the first item with day name and button
                        const dailyRecipeItem1 = document.createElement('div');
                        dailyRecipeItem1.classList.add('daily-recipe-item-day');

                        const dayNameH1 = document.createElement('h1');
                        dayNameH1.classList.add('day-name');
                        dayNameH1.textContent = currentDay.dayFull;

                        const removeButton = document.createElement('a');
                        removeButton.classList.add('rounded-button');
                        removeButton.id = 'removeBtn';
                        removeButton.textContent = 'Remove';

                        removeButton.addEventListener('click', function() {
                            var xhrRemove = new XMLHttpRequest();
                            xhrRemove.open("GET", `https://cgi.luddy.indiana.edu/~team30/saved-content/remove-day.php?currentId=${currentDay.id}&day=${currentDay.day}`, true);

                            xhrRemove.onload = function () {
                                if (xhrRemove.status >= 200 && xhrRemove.status < 300) {
                                    window.location.href = 'https://cgi.luddy.indiana.edu/~team30/for-you.php';
                                } else {
                                        // Handle request errors here
                                        console.error('Error:', xhrRemove.statusText);
                                }
                            };
                            xhrRemove.send();
                        });

                        dailyRecipeItem1.appendChild(dayNameH1);
                        dailyRecipeItem1.appendChild(removeButton);

                        // Create the second item with video
                        const dailyRecipeItem2 = document.createElement('div');
                        dailyRecipeItem2.classList.add('daily-recipe-item');

                        const videoContainer = document.createElement('div');
                        videoContainer.classList.add('s-video-container');

                        var image = document.createElement('img');
                        image.id = 'imagePlayer';
                        image.classList.add('rounded-image'); // Ensure you have CSS for this class
                        image.src = currentDay.url; // Concatenate the path with the image URL
                        image.alt = 'Description';

                        videoContainer.appendChild(image);
                        dailyRecipeItem2.appendChild(videoContainer);

                        // Create the third item with recipe title and ingredients list
                        const dailyRecipeItem3 = document.createElement('div');
                        dailyRecipeItem3.classList.add('daily-recipe-item');

                        const titleSavedH1 = document.createElement('h1');
                        titleSavedH1.classList.add('s-recipe');
                        titleSavedH1.textContent = currentDay.title;

                        const ingredientsTitleH1 = document.createElement('strong');
                        ingredientsTitleH1.classList.add('s-ingredients-title');
                        ingredientsTitleH1.textContent = 'Ingredients';

                        const ingredientsListUl = document.createElement('ul');
                        ingredientsListUl.id = 'ingredients-list-saved';
                        ingredientsListUl.classList.add('s-ingredients-list');

                        dailyRecipeItem3.appendChild(titleSavedH1);
                        dailyRecipeItem3.appendChild(ingredientsTitleH1);
                        dailyRecipeItem3.appendChild(ingredientsListUl);

                        currentDay.ingrarray.forEach(function(element) {
                            const newIngredient = document.createElement('li');
                            newIngredient.textContent = element;
                            ingredientsListUl.appendChild(newIngredient);
                        });

                        // Append all items to the main container
                        dailyRecipeDiv.appendChild(dailyRecipeItem1);
                        dailyRecipeDiv.appendChild(dailyRecipeItem2);
                        dailyRecipeDiv.appendChild(dailyRecipeItem3);

                        // Finally, append the main container to the body or a specific element in your page
                        const weeklyWrapper = document.getElementById('weeklyMpWrapperFY');
                        weeklyWrapper.appendChild(dailyRecipeDiv);
                        // Or use document.getElementById('someElementId').appendChild(dailyRecipeDiv); to append it to a specific element

                    });

                } else {
                    console.error('Error:', xhrSaved.statusText);
                }
            };
        
        var mealplan = document.getElementById('weekly-wrapper');
        mealplan.style.display = 'none';
            
        xhrSaved.send()
    }

    function changeTiles(currentId) {
        var xhrThree = new XMLHttpRequest();
        xhrThree.open("GET", `https://cgi.luddy.indiana.edu/~team30/for-you-content/get-tiles.php?currentId=${currentId}`, true);

        xhrThree.onload = function () {
            if (xhrThree.status >= 200 && xhrThree.status < 300) {
                
                var dataThree = JSON.parse(xhrThree.responseText);
                const videoGall = document.getElementById('videogallery');
                videoGall.innerHTML = '';

                dataThree.forEach(function(row) {
                    let div = document.createElement('div');
                    div.className = 'video-item';
                
                    let thumbnailDiv = document.createElement('div');
                    thumbnailDiv.className = 'video-thumbnail';
                
                    let video = document.createElement('video');
                    video.controls = true;
                    video.className = 'rounded-video';
                    let source = document.createElement('source');
                    source.src = 'content/' + row.video;
                    source.type = 'video/mp4';
                    video.appendChild(source);            
                    thumbnailDiv.appendChild(video);
                
                    let overlayDiv = document.createElement('div');
                    overlayDiv.className = 'like-overlay';
                    let heartSpan = document.createElement('span');
                    heartSpan.className = 'heart-icon';
                    heartSpan.textContent = '❤';
                    let countSpan = document.createElement('span');
                    countSpan.className = 'like-count';
                    countSpan.textContent = row.likes;
                    overlayDiv.appendChild(heartSpan);
                    overlayDiv.appendChild(countSpan);
                
                    thumbnailDiv.appendChild(overlayDiv);
                    div.appendChild(thumbnailDiv);
                
                    let titleH4 = document.createElement('h4');
                    titleH4.className = 'video-title';
                    titleH4.textContent = row.name;
                    div.appendChild(titleH4);
                
                    videoGall.appendChild(div); // Append to the body or a specific container
                });
                
            } else {
                // Handle request errors here
                console.error('Error fetching video:', xhrThree.statusText);
            }
        };

        xhrThree.onerror = function () {
            console.error('Network error.');
        };
        xhrThree.send();
        console.log(xhrThree.responseText);
    }

    function changeVideo(currentId, direction) {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", `https://cgi.luddy.indiana.edu/~team30/for-you-content/get-video.php?direction=${direction}&currentId=${currentId}`, true);

        xhr.onload = function () {
            if (xhr.status >= 200 && xhr.status < 300) {
                // Parse the JSON response
                var data = JSON.parse(xhr.responseText);
                const videoName = document.getElementById('videoUser');
                const videoTitle = document.getElementById('videoTitle');
                videoTitle.textContent = data.title;
                /* videoName.textContent = data.name;
 */
                var videoContainer = document.getElementById('videoContainer');
                videoContainer.innerHTML = '';
                videoContainer.setAttribute('datacurrentid', data.id);

                var video = document.createElement('video');
                video.setAttribute('controls', '');
                video.classList.add('rounded-video');

                // Create the source element
                var source = document.createElement('source');
                source.id = 'videoPlayer';
                source.src = 'content/' + data.url; // Concatenate the path with the video URL
                source.type = 'video/mp4';

                // Append the source to the video
                video.appendChild(source);
                videoContainer.appendChild(video);
            } else {
                // Handle request errors here
                console.error('Error fetching video:', xhr.statusText);
            }
        };

        xhr.onerror = function () {
            console.error('Network error.');
        };
        xhr.send();
    }

    function changeIngredients(currentId) {
        var xhrFour = new XMLHttpRequest();
        xhrFour.open("GET", `for-you-content/get-ingredients.php?currentId=${currentId}`, true);

        xhrFour.onload = function () {
            if (xhrFour.status >= 200 && xhrFour.status < 300) {
            var dataFour = JSON.parse(xhrFour.responseText);
                const ingredients = document.getElementById('ingredients-list');
                // const steps = document.getElementById('steps');
                ingredients.innerHTML = '';
                // steps.textContent = dataFour.stps;
                
                dataFour.ingrarray.forEach(function(element) {
                    const newIngredient = document.createElement('li');
                    newIngredient.textContent = element;
                    ingredients.appendChild(newIngredient);
                }); 
                
                // var h2Element = document.createElement('h2');
                // h2Element.className = 'ingredients-title';
                // h2Element.textContent = 'Steps';
                // const stepsDiv = document.getElementById('stepsDiv');
                // stepsDiv.appendChild(h2Element);

            } else {
                // Handle request errors here
                console.error('Error fetching video:', xhrFour.statusText);
            }
        };

        xhrFour.onerror = function () {
            console.error('Network error.');
        };
        xhrFour.send();
        console.log("xhrFour");
        console.log(xhrFour.responseText);
    }
        
    function changeApiTiles() {
        var xhrApiTiles= new XMLHttpRequest();
        xhrApiTiles.open("GET", `https://cgi.luddy.indiana.edu/~team30/for-you-content/recipe-api.php`, true);

        xhrApiTiles.onload = function () {
            if (xhrApiTiles.status >= 200 && xhrApiTiles.status < 300) {
                
                var dataApiTiles = JSON.parse(xhrApiTiles.responseText);
                const videoGall = document.getElementById('videogallery');
                videoGall.innerHTML = '';

                dataApiTiles.tilesArray.forEach(function(row) {
                    let div = document.createElement('div');
                    div.className = 'video-item';
                
                    let thumbnailDiv = document.createElement('div');
                    thumbnailDiv.className = 'video-thumbnail';
                
                    let image = document.createElement('img'); // Create an img element instead of video
                    image.className = 'rounded-image'; // Use a class name suitable for your image
                    image.src = row.images; // Assuming row.image contains the image filename
                    image.alt = 'Description'; // Provide an alt text for the image
                    thumbnailDiv.appendChild(image); // Append the image to thumbnailDiv

                    div.appendChild(thumbnailDiv);
                
                    let titleH4 = document.createElement('h4');
                    titleH4.className = 'video-title';
                    titleH4.textContent = row.name;
                    div.appendChild(titleH4);
                
                    videoGall.appendChild(div); // Append to the body or a specific container
                });
                
            } else {
                // Handle request errors here
                console.error('Error fetching video:', xhrApiTiles.statusText);
            }
        };

        xhrApiTiles.send();
    }

    function changeApiImg() {
        var xhrApiImg = new XMLHttpRequest();
        xhrApiImg.open("GET", `https://cgi.luddy.indiana.edu/~team30/for-you-content/recipe-api.php`, true);

        xhrApiImg.onload = function () {
            if (xhrApiImg.status >= 200 && xhrApiImg.status < 300) {
                // Parse the JSON response
                var dataApiImg = JSON.parse(xhrApiImg.responseText);
                const videoName = document.getElementById('videoUser');
                const videoTitle = document.getElementById('videoTitle');
                videoTitle.textContent = dataApiImg.title;

                var videoContainer = document.getElementById('videoContainer');
                videoContainer.innerHTML = '';
                videoContainer.setAttribute('datacurrentidAPI', dataApiImg.id);

                // Create the image element
                var image = document.createElement('img');
                image.id = 'imagePlayer';
                image.classList.add('rounded-image'); // Ensure you have CSS for this class
                image.src = dataApiImg.url; // Concatenate the path with the image URL
                image.alt = 'Description'; // Provide a fallback description for the image

                // Append the image to the container
                videoContainer.appendChild(image);
                
            } else {
                // Handle request errors here
                console.error('Error fetching video:', xhrApiImg.statusText);
            }
        };

        xhrApiImg.onerror = function () {
            console.error('Network error.');
        };
        xhrApiImg.send();
    }
        
    function apiIngredients() {
        var xhrApiIng = new XMLHttpRequest();
        xhrApiIng.open("GET", `https://cgi.luddy.indiana.edu/~team30/for-you-content/recipe-api.php`, true);

        xhrApiIng.onload = function () {
            if (xhrApiIng.status >= 200 && xhrApiIng.status < 300) {
                var dataApiIng = JSON.parse(xhrApiIng.responseText);
                const ingredients = document.getElementById('ingredients-list');
                ingredients.innerHTML = '';
                
                dataApiIng.ingrarray.forEach(function(ingredient) {
                    const newIngredient = document.createElement('li');
                    newIngredient.textContent = ingredient;
                    ingredients.appendChild(newIngredient);
                });


                console.log(dataApiIng);

            } else {
                // Handle request errors here
                console.error('Error fetching video:', xhrApiIng.statusText);
            }
        };

        xhrApiIng.onerror = function () {
            console.error('Network error.');
        };
        xhrApiIng.send();
        console.log(xhrApiIng.responseText);
    }

    function fetchVideo(direction, currentId, api) {
        if (api == ""){   
            // changeTiles(currentId); 
            changeVideo(currentId, direction);
            changeIngredients(currentId);
        } else {
            // run api content
            // changeTiles(currentId);
            apiIngredients();
            changeApiImg();
        }
    }

});