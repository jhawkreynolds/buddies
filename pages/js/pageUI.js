//================
// PAGE ASSEMBLERS

// assembles the page's header when run
function build_pageheader()
{
    // HEADING
    let pageheader_heading = document.createElement("h1");
    let pageheader_heading_text = document.createTextNode("Buddies Project");
    pageheader_heading.appendChild(pageheader_heading_text);

    // OPTIONS TABLE
    let pageheader_options_table = document.createElement("table");
    let pageheader_options_tbody = document.createElement("tbody");
    let pageheader_options_table_tr = document.createElement("tr");
    pageheader_options_table_tr.setAttribute("class", "ribbon");

    // HOME BUTTON
    let pageheader_home_button = document.createElement("button");
    pageheader_home_button.setAttribute("onclick", "redirect('home')");
    pageheader_home_button.setAttribute("class", "ribbonbutton");
    let pageheader_home_button_text = document.createTextNode("Home");
    pageheader_home_button.appendChild(pageheader_home_button_text);

    // APPEND HOME BUTTON
    let pageheader_home_td = document.createElement("td");
    pageheader_home_td.appendChild(pageheader_home_button);
    pageheader_options_table_tr.appendChild(pageheader_home_td);
    
    if(is_logged_in())
    {
        // ACCOUNT BUTTON
        let pageheader_account_button = document.createElement("button");
        pageheader_account_button.setAttribute("onclick", "redirect('account')");
        pageheader_account_button.setAttribute("class", "ribbonbutton");
        let pageheader_account_button_text = document.createTextNode("Account");
        pageheader_account_button.appendChild(pageheader_account_button_text);

        // APPEND ACCOUNT BUTTON
        let pageheader_account_td = document.createElement("td");
        pageheader_account_td.appendChild(pageheader_account_button);
        pageheader_options_table_tr.appendChild(pageheader_account_td);

        // FIND BUTTON
        let pageheader_find_button = document.createElement("button");
        pageheader_find_button.setAttribute("onclick", "redirect('find')");
        pageheader_find_button.setAttribute("class", "ribbonbutton");
        let pageheader_find_button_text = document.createTextNode("Find Buddies");
        pageheader_find_button.appendChild(pageheader_find_button_text);

        // APPEND FIND BUTTON
        let pageheader_find_td = document.createElement("td");
        pageheader_find_td.appendChild(pageheader_find_button);
        pageheader_options_table_tr.appendChild(pageheader_find_td);

        // LOGOUT BUTTON
        let pageheader_logout_button = document.createElement("button");
        pageheader_logout_button.setAttribute("onclick", "redirect('logout')");
        pageheader_logout_button.setAttribute("class", "ribbonbutton");
        let pageheader_logout_button_text = document.createTextNode("Log Out");
        pageheader_logout_button.appendChild(pageheader_logout_button_text);

        // APPEND LOGOUT BUTTON
        let pageheader_logout_td = document.createElement("td");
        pageheader_logout_td.appendChild(pageheader_logout_button);
        pageheader_options_table_tr.appendChild(pageheader_logout_td);

    }
    else
    {
        // LOGIN BUTTON
        let pageheader_login_button = document.createElement("button");
        pageheader_login_button.setAttribute("onclick", "redirect('login')");
        pageheader_login_button.setAttribute("class", "ribbonbutton");
        let pageheader_login_button_text = document.createTextNode("Log In");
        pageheader_login_button.appendChild(pageheader_login_button_text);

        // APPEND LOGIN BUTTON
        let pageheader_login_td = document.createElement("td");
        pageheader_login_td.appendChild(pageheader_login_button);
        pageheader_options_table_tr.appendChild(pageheader_login_td);

        // REGISTER BUTTON
        let pageheader_register_button = document.createElement("button");
        pageheader_register_button.setAttribute("onclick", "redirect('register')");
        pageheader_register_button.setAttribute("class", "ribbonbutton");
        let pageheader_register_button_text = document.createTextNode("Register");
        pageheader_register_button.appendChild(pageheader_register_button_text);

        // APPEND REGISTER BUTTON
        let pageheader_register_td = document.createElement("td");
        pageheader_register_td.appendChild(pageheader_register_button);
        pageheader_options_table_tr.appendChild(pageheader_register_td);
    }

    // FINISH CREATION
    pageheader_options_tbody.appendChild(pageheader_options_table_tr);
    pageheader_options_table.appendChild(pageheader_options_tbody);
    let pageheader = document.getElementById('pageheader');
    pageheader.appendChild(pageheader_heading);
    pageheader.appendChild(pageheader_options_table);    
}

// assembles the page's footer when run
function build_pagefooter()
{
    let pagefooter_paragraph = document.createElement("p");
    let pagefooter_paragraph_text = document.createTextNode("Developed by Jack Reynolds in 2023 for Grace Pearson");
    pagefooter_paragraph.appendChild(pagefooter_paragraph_text);

    let pagefooter = document.getElementById("pagefooter");
    pagefooter.append(pagefooter_paragraph);
}

//============
// REDIRECT TOOLS
function redirect(location)
{
    switch(location)
    {
        case 'home':
            window.location.href = "../home/";
            break;
        case 'account':
            window.location.href = "../account/";
            break;
        case 'find':
            window.location.href = "../find/";
            break;
        case 'logout':
            window.location.href = "../logout/";
            break;
        case 'login':
            window.location.href = "../login/";
            break;
        case 'register':
            window.location.href = "../register/";
            break;
    }
}

//============
// LOGIN TOOLS

// returns bool depending on whether the user is logged in
// requires a check of the cookie
var login_flag = false
function is_logged_in()
{
    // temporary code
    let cookie_value = get_cookie("buddies-login");
    if(cookie_value == "")
    {
        return false;
    }
    else
    {
        let cookie_JSON = JSON.parse(decodeURIComponent(cookie_value));
        let username = "";
        try{
            username = cookie_JSON.username; 
        }
        catch(error){
            // someone has assigned the cookie to a different value.
            return false;
        }
        login_flag = false;
        load_doc(`../../../buddies-data/session/${username}.json`, http_request_json, cookie_JSON.hash);
    
        console.log('This should be second!');
        return login_flag;
    }
}

// finds a cookie stored client-side
// returns the cookie value if cookie exists
// returns an empty string if cookie does not exist
function get_cookie(cname) {
    let name = cname + "=";
    let ca = document.cookie.split(';');
    for(let i = 0; i < ca.length; i++) {
      let c = ca[i];
      while (c.charAt(0) == ' ') {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
      }
    }
    return "";
}

// returns xttp.responseText as is
function http_request_json(xhttp, hash) {
    console.log(xhttp.responseText);
    let http_JSON = JSON.parse(xhttp.responseText);
    if(hash == http_JSON.hash) login_flag = true;
}

// makes an AJAX call to url and runs cFunction with the data
function load_doc(url, cFunction, hash) {
    var xhttp;
    xhttp=new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        cFunction(this, hash);
      }
    };
    xhttp.open("GET", url, true);
    xhttp.send();
}