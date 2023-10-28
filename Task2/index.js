const express = require('express');
const app = express();

const bodyParser = require('body-parser');
const mongoose = require('mongoose');
const _ = require("lodash");

const port = 3000;

app.set('view engine', 'ejs');
app.use(express.static("public"));
app.use(bodyParser.urlencoded({ extended: true }));

// Defineing array
// const todayArray = [];

// =======================================================
// Creating a connection & connecting the db
// mongodb+srv://admin-vishesh:< >@cluster0.2for305.mongodb.net/?retryWrites=true&w=majority
mongoose.connect('mongodb://127.0.0.1:27017/todolistdb', {
    useNewUrlParser: true,
    useUnifiedTopology: true,
});

// =======================================================
// Creating Schema
const itemsSchema = {
    name: String
};

// Adding Model -> 'Item' & Creating Collection -> 'products'
const Item = mongoose.model("products", itemsSchema);

// Creating Default List Item array of objets
const defaultItems = [
    { name: "Welcome to your to-do list" },
    { name: "Hit the button to add a new item" },
    { name: "<--hit this to delete an item." }
];

// Creating array for the new custom list
const listSchema = {
    name: String,
    items: [itemsSchema]
};
const List = mongoose.model("List", listSchema);


/*
// This Below code to push  or Insert the data to the collections everytime you run the code Dummy Data
Item.insertMany(defaultItems);
*/

let today = new Date();
let options = {
    weekday: "long",
    day: "numeric",
    month: "long"
};
let day = today.toLocaleDateString("en-US", options);
// =======================================================

// Start ==================================
// this below code is for 
app.get('/', async(req, res) => {
    const docs = await Item.find({});

    if (docs.length === 0) {
        if (Item.insertMany(defaultItems)) {
            console.log('Successfully saved default items to DB.');
        } else {
            console.log('err');
        }
        res.redirect('/');
    } else {
        res.render("list", {
            Title: "Today",
            todaysDate: day,
            newListItems: docs
        });
    }
});
// ============ END ============

// Adding Item ============================
// Start ==================================
app.post('/adding', async(req, res) => {
    const itemName = req.body.newtask;
    const btnValue = req.body.listbtn;
    const item = new Item({ name: itemName });

    if (btnValue === "Today") {
        await item.save();
        res.redirect('/');
    } else {
        const found = await List.findOne({});
        if (found) {
            found.items.push(item);
            await found.save();
            res.redirect("/" + btnValue);
        }
    }
});
// End ==================================

// Deleting List Items
app.post('/delete', async(req, res) => {
    const checkId = req.body.checkItem;
    const listName = req.body.listName;
    // console.log(checkId);
    // console.log(listName);
    if (listName === "Today") {
        try {
            const deletedItem = await Item.findByIdAndRemove(checkId);
            if (deletedItem) {
                res.redirect('/');
            } else {
                res.sendStatus(404); // Send a not found response
            }
        } catch (error) {
            console.error("Error deleting item:", error);
            res.sendStatus(500); // Send an internal server error response
        }
    } else {
        // console.log("yes");
        try {
            const result = await List.findOneAndUpdate({ name: listName }, { $pull: { items: { _id: checkId } } });

            if (result) {
                // The update was successful
                res.redirect("/" + listName);
            } else {
                // No document was found matching the criteria
                res.redirect("/" + listName);
            }
        } catch (error) {
            console.error("Error while deleting:", error);
            res.redirect("/" + listName);
        }

    }

});

// Creating Custom entriens
app.get('/:customListName', async(req, res) => {
    const customListName = _.capitalize(req.params.customListName);
    if (customListName !== '' && customListName !== 'Favicon.ico') {
        const found = await List.findOne({ name: customListName });
        if (found) {
            // console.log("Already Exists!");
            res.render("list", {
                Title: found.name,
                todaysDate: day,
                newListItems: found.items
            });
        } else {
            // console.log('Dont Exixts So Data is Created Successfully!');
            const list = new List({
                name: customListName,
                items: defaultItems
            });
            await list.save();
        }
    }
});

app.listen(port, () => {
    console.log(`Listening on port ${port}`);
});