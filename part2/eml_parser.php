<?php
    // Function to parse EML content into HTML using regular expressions
    function parseEMLToHTML($eml_content) {
        // Replace <h1> tags with corresponding HTML header tags
        $eml_content = preg_replace('/<h1>(.*?)<\/h1>/', '<h1>$1</h1>', $eml_content);

        // Replace <h2> tags with corresponding HTML header tags
        $eml_content = preg_replace('/<h2>(.*?)<\/h2>/', '<h2>$1</h2>', $eml_content);

        // Replace <h3> tags with corresponding HTML header tags
        $eml_content = preg_replace('/<h3>(.*?)<\/h3>/', '<h3>$1</h3>', $eml_content);

        // Replace <p> tags with corresponding HTML paragraph tags
        $eml_content = preg_replace('/<p>(.*?)<\/p>/', '<p>$1</p>', $eml_content);

        // Replace <ul> tags with corresponding HTML unordered list tags
        $eml_content = preg_replace('/<ul>(.*?)<\/ul>/', '<ul>$1</ul>', $eml_content);

        // Replace <li> tags with corresponding HTML list item tags
        $eml_content = preg_replace('/<li>(.*?)<\/li>/', '<li>$1</li>', $eml_content);

        // Add more regular expressions for other EML tags as needed

        return $eml_content;
    }
?>