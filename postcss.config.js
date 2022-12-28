mix.js("resources/js/app.js", "public/js").postCss(
    "resources/css/app.css",
    "public/css",
    [
        // add here
        require("tailwindcss"),
    ]
);
