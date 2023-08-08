
CREATE TABLE eml (
    content_id INT PRIMARY KEY AUTO_INCREMENT,
    content_type VARCHAR(255) NOT NULL,
    parent_id INT,
    content_title VARCHAR(255) NOT NULL,
    content_description TEXT,
    content TEXT,
    course_id INT,
    date_created DATETIME NOT NULL,
    date_modified DATETIME NOT NULL
);
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(255) NOT NULL
);
CREATE TABLE grades (
    grade_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    course_name VARCHAR(255) NOT NULL,
    grade DECIMAL(5, 2) NOT NULL
);
CREATE TABLE quiz_questions (
    question_id INT PRIMARY KEY AUTO_INCREMENT,
    quiz_id INT NOT NULL,
    question_text TEXT NOT NULL,
    option1 VARCHAR(255) NOT NULL,
    option2 VARCHAR(255) NOT NULL,
    option3 VARCHAR(255) NOT NULL,
    correct_option VARCHAR(255) NOT NULL,
    FOREIGN KEY (quiz_id) REFERENCES eml(content_id)
);