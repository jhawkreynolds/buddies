//================
// PAGE ASSEMBLERS

// assembles the page's header when run
function build_pageheader()
{
    let pageheader_heading = document.createElement("h1");
    let pageheader_heading_text = document.createTextNode("Buddies Project");
    pageheader_heading.appendChild(pageheader_heading_text);

    //let pageheader

    let pageheader_table = document.createElement("table");
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
// LOGIN TOOLS

// returns bool depending on whether the user is logged in
// requires a check of the cookie
function is_logged_in()
{
    // temporary code
    return true;
}