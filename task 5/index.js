const express = require("express");
const bodyParser = require('body-parser');
const ejs = require('ejs');
const session = require('express-session');
const mongoose = require('mongoose');

const port = 3000;
const app = express();

app.use(express.static("public"));
app.set('view engine', 'ejs');
app.use(bodyParser.urlencoded({
    extended: true
}));

app.use(session({
    secret: 'vishesh', // Replace 'yourSecretKey' with a secret string for session encryption
    resave: false,
    saveUninitialized: false
}));

mongoose.connect("mongodb://127.0.0.1:27017/LibDB", { useNewUrlParser: true });

const userSchema = new mongoose.Schema({
    user_name: String,
    user_email: String,
    user_password: String
});

// ("collection", entries{email, password})
const User = new mongoose.model("logintable", userSchema);

app.use(express.static("public"));
app.set('view engine', 'ejs');
app.use(bodyParser.urlencoded({
    extended: true
}));

// GET URL TO Login
app.get("/login", (req, res) => {
    const title = "Login Page";
    const label = "Login page";
    res.render("login", {
        title,
        label
    });
});

// GET URL TO SIGNUP
app.get("/signup", (req, res) => {
    const title = "Sign Up Page";
    const label = "Sign-Up";
    res.render("signup", {
        title,
        label
    });
});

app.get("/history", (req, res) => {
    res.render("history");
});

// GET DASH or HOME PAGE
app.get("/dash", (req, res) => {
    const userEmail = req.session.userEmail;
    if (userEmail) {
        res.render("dash", {
            title: "Library Page",
            userEmail: userEmail
        });
    } else {
        res.redirect("login");
    }
});

// POST SIGNUP FUNCTION
app.post("/signup", async(req, res) => {
    const { user_email, user_password, user_name } = req.body;

    try {
        const findUser = await User.findOne({ user_email: user_email });
        if (!findUser) {
            const newuser = new User({
                user_name: user_name,
                user_email: user_email,
                user_password: user_password
            });
            newuser.save().then(() => {
                console.log(`${user_email} is registered!`);
                req.session.userEmail = user_email;
                // res.render('dash');
                res.redirect('/dash');
            });

        } else {
            console.log("User already exists.");
            res.send('failure');
        }
    } catch (error) {
        console.log(error);
        res.status(500).send("Internal Server Error");
    }
});

// POST LOGIN FUNCTION
app.post("/login", async(req, res) => {
    const { user_email, user_password } = req.body;

    const findUser = await User.findOne({ user_email: user_email });

    try {
        if (findUser) {
            req.session.userEmail = findUser.user_email;
            // console.log("yes");
            res.redirect('dash');
        }
    } catch (error) {
        console.log(error);
        res.status(500).send("Internal Server Error");
    }
});

// LOGOUT FUNCTIONALITIES
app.get('/logout', (req, res) => {
    req.session.destroy(err => {
        if (err) {
            console.log(err);
            res.status(500).send("Error logging out");
        } else {
            res.redirect('/login'); // Redirect to the login page after logout
        }
    });
});

// LISTING ON POST 3000
app.listen(port, () => {
    console.log(`listening on ${port}`);
});