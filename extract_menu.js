const fs = require('fs');

// Read app.js
let appJs = fs.readFileSync('js/app.js', 'utf8');

// Use regex or eval to get the MENU object
// Because it's just a JS object, we can extract it.
let match = appJs.match(/const MENU = (\{[\s\S]*?\});\s*\/\//);
if (match) {
    let menuStr = match[1];
    // Need to safely eval it to get the object
    let menuObj = eval("(" + menuStr + ")");
    fs.writeFileSync('menu_data.json', JSON.stringify(menuObj, null, 2));
    console.log("Menu extracted to menu_data.json");
} else {
    console.log("Could not find MENU in app.js");
}
