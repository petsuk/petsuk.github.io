DROP TABLE IF EXISTS RecipeIngredients;
DROP TABLE IF EXISTS UserRecipes;
DROP TABLE IF EXISTS RecipeIngredients;
DROP TABLE IF EXISTS History;
DROP TABLE IF EXISTS GroceryList;
DROP TABLE IF EXISTS SavedMP;
DROP TABLE IF EXISTS MealPlan;
DROP TABLE IF EXISTS Recipes;
DROP TABLE IF EXISTS Ingredients;
DROP TABLE IF EXISTS User;


CREATE TABLE User (
id INT AUTO_INCREMENT,
username VARCHAR(40) NOT NULL,
email VARCHAR(40) NOT NULL,
fname VARCHAR(30) NOT NULL,
lname VARCHAR(30) NOT NULL,
age INT NOT NULL,
restr VARCHAR(40),
PRIMARY KEY (id)
) ENGINE = innodb;




CREATE TABLE Ingredients (
id INT AUTO_INCREMENT,
name VARCHAR(200) NOT NULL,
img VARCHAR(200) NOT NULL,
description VARCHAR(300) NOT NULL,
unit VARCHAR(10) NOT NULL,
PRIMARY KEY (id)
) ENGINE = innodb;


-- Place for recipes
CREATE TABLE Recipes (
    id INT AUTO_INCREMENT,
    userID INT NOT NULL,
    name VARCHAR(200) NOT NULL,
    Description VARCHAR(300) NOT NULL,
    Time INT NOT NULL,
    Steps VARCHAR(255) NOT NULL, 
    NutritionInfo VARCHAR(255), 
    watched BOOLEAN,
    saved VARCHAR(11),
    video VARCHAR(250),
    likes INT,
    ingredients VARCHAR(2000),
    PRIMARY KEY (id),
    FOREIGN KEY (userID) REFERENCES Users(id)
) ENGINE = innodb;

CREATE TABLE MealPlan (
recID INT NOT NULL,
day VARCHAR(15) NOT NULL,
FOREIGN KEY (recID) REFERENCES Recipes(id)
) ENGINE = innodb;

CREATE TABLE SavedMP (
mpID INT,
recID INT NOT NULL,
day VARCHAR(15) NOT NULL,
MealDate VARCHAR(10) NOT NULL,
FOREIGN KEY (recID) REFERENCES Recipes(id)
) ENGINE = innodb;

CREATE TABLE GroceryList (
id INT AUTO_INCREMENT,
itemid INT,
recipeID INT,
FOREIGN KEY(itemid) REFERENCES Ingredients(id),
FOREIGN KEY(recipeID) REFERENCES Recipes(id),
PRIMARY KEY (id)
) ENGINE = innodb;


CREATE TABLE RecipeIngredients (
    RecipeID INT,
    IngredientID INT,
    quantity INT,
    PRIMARY KEY (RecipeID, IngredientID),
    FOREIGN KEY (RecipeID) REFERENCES Recipes(id),
    FOREIGN KEY (IngredientID) REFERENCES Ingredients(id)
)ENGINE = innodb;


--Need a function that loads the session
-- use RowID 

--Maybe need to add a transactional table for 
/* 
Add to recipes table:
- Video file path
- Video watched boolean
- Liked?
- Shared? 
*/

-- Need about 5 examples each

INSERT INTO User(id,username, email, fname, lname, age, restr) VALUES
    (1, 'jhl@gmail.com','JHLdog', 'Jorge', 'Levving', 23, 'Vegetarian'),
    (2, 'DJ@gmail.com','DJthedj' , 'Dana', 'Jones', 25, 'Gluten Inteolerance'),
    (3, 'livcrei@gmail.com','creinkicker', 'Olivia', 'Crein', 19, 'Keto'),
    (4, 'tjd@gmail.com', 'opendoer', 'Tyler', 'Doering', 29, 'Vegan'),
    (5, 'sford@gmail.com', 'sydford','Sydney', 'Ford', 24, 'Lactose Intolerance') ;

INSERT INTO Recipes(id, userID,name, description, Time, Steps, NutritionInfo,watched, saved, video,likes, ingredients) VALUES
    (1, 1,'Dude Dinner', 'Classic Italian pasta dish with meat sauce', 30, 'Cook pasta; prepare meat sauce; combine and serve.', 'Calories: 500, Protein: 20g, Fat: 10g, Carbs: 70g', FALSE, '2023-11-12', 'video_example.mov', 30, '3,g,pasta;3,cups,alfredo;2,chicken breast'),
    (2, 2,'Chicken Stir Fry', 'Healthy stir-fried chicken with vegetables', 25, 'Marinate chicken; stir-fry with vegetables; season and serve.', 'Calories: 400, Protein: 25g, Fat: 15g, Carbs: 40g', FALSE, '', 'Video.mov',0, '1,lbs,chicken;3,tspn,soy sauce;1,cup,teriaki sauce'),
    (3, 1,'Vegetarian Pizza', 'Delicious pizza with assorted vegetables', 20, 'Prepare pizza dough; add sauce and vegetables; bake until golden.', 'Calories: 350, Protein: 15g, Fat: 12g, Carbs: 45g', TRUE, '2023-04-07', 'Video_1.mov', 0,'1,tomato;2,tspns,vinegrette;3,cups,flour'),
    (4, 3,'Grilled Salmon', 'Grilled salmon fillet with lemon and herbs', 15, 'Marinate salmon; grill until cooked; squeeze lemon and garnish with herbs.', 'Calories: 300, Protein: 30g, Fat: 18g, Carbs: 5g', FALSE, '', 'Video_2.mov', 0 , '1,salmon fillet;lemon pepper seasoning;1,cup,mayo'),
    (5, 3,'Chocolate Cake', 'Moist chocolate cake with rich frosting', 40, 'Mix ingredients; bake in preheated oven; frost when cooled.', 'Calories: 450, Protein: 5g, Fat: 25g, Carbs: 55g', TRUE, '', 'Video_3.mov', 0, 'chocolate cake mix;3,cups,milk'),
    (6, 2,'Spaghetti Bolognese', 'Classic Italian pasta dish with meat sauce', 30, 'Cook pasta; prepare meat sauce; combine and serve.', 'Calories: 500, Protein: 20g, Fat: 10g, Carbs: 70g', true, '', 'Video_4.mov', 25, '3,g,spaghetti;4,strips,bacon;2,cups,spaghetti sauce');

INSERT INTO Ingredients(id, name, img, description, unit) VALUES
    (1, 'Apples', 'apple.jpg', 'Fresh and crisp apples, great for snacking or baking.', 'no unit'),
    (2, 'Milk', 'milk_carton.jpg', 'Whole milk, rich and creamy, perfect for drinking or cooking.', 'gal'),
    (3, 'Bread', 'bread_loaf.jpg', 'Freshly baked bread, ideal for sandwiches or toasting.', 'g'),
    (4, 'Spinach', 'spinach_bundle.jpg', 'Nutrient-packed spinach, perfect for salads or sautéing.', 'g'),
    (5, 'Chicken Breast', 'chicken_breast.jpg', 'Boneless and skinless chicken breasts, versatile for various recipes.', 'lbs');

INSERT INTO GroceryList(id,itemid, RecipeID) VALUES
    (1,3, 4),
    (2,4, 2),
    (3,5, 1),
    (4,1, 3),
    (5,2, 2);



--quantity was put in there instead of in the ingredient table because different recipes could ask for more quantity

INSERT INTO RecipeIngredients(RecipeID, IngredientID, quantity) VALUES
    (1,3, 4),
    (2,4, 2),
    (3,5, 1),
    (4,1, 3),
    (1,2,3);



INSERT INTO UserRecipes(UserID, RecipeID) VALUES
    (1,1),
    (2,2);


INSERT INTO profileimg (userid, status) VALUES
(1, 1), 
(2, 1),
(3, 1),
(4, 1),
(5, 1);

--Need to make a SELECT statement for Recipe Ingredients
-- Query Below to see ingredient the unit in which it is quantified and the quantity
/* 
SELECT i.name, i.unit, ri.quantity 
FROM Recipes as r 
JOIN RecipeIngredients as ri ON ri.RecipeID = r.id
JOIN Ingredients as i ON i.id = ri.IngredientID
WHERE r.id = 1 ;
 */

--Shows steps on for you
 -- SELECT Steps FROM Recipes WHERE id = 1;

--code for for you page

/*
echo "<center><table class='table table-striped'> <th></th> <th></th> <th></th>";
echo  "<tr>";
echo "<td>" . $row["i.name"] . "</td>";
echo "<td>" . $row["i.unit"] . "</td>";
echo "<td>" . $row["ri.quantity"] . "</td>";
echo  "</tr>";
echo "</table> </center>";
*/


/*

 

SELECT
ROW_NUMBER() (ORDER BY likes) as RowID,
video, 
MAX(CAST(likes AS UNSIGNED)) AS MostLikes
                    FROM Recipes
                    WHERE watched = FALSE
                    GROUP BY video
                    ORDER BY MostLikes DESC
                    LIMIT 1;


Qu
SELECT 
    ROW_NUMBER() OVER (
            ORDER BY likes DESC
    ) as RowID, 
    video, 
    likes 
FROM
    Recipes

WHERE watched = false
LIMIT 1
;




ONE BIG REUSIBLE QUERY FOR ALL FOR-YOU-CONTENT

SELECT i.name, i.unit, ri.quantity, CONCAT(u.fname, u.lname, ' '), r.video, r.name, r.id
            FROM Recipes as r 
            JOIN RecipeIngredients as ri ON ri.RecipeID = r.id
            JOIN Ingredients as i ON i.id = ri.IngredientID
            JOIN UserRecipes as ur ON ur.RecipeID = r.id
            JOIN User as u ON u.id = ur.UserID
            WHERE r.id = 1;


*/

