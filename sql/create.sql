CREATE TABLE Movie (
    id int PRIMARY KEY,
    title VARCHAR(100),
    year int,
    rating VARCHAR(10),
    company VARCHAR(50)
);

CREATE TABLE Actor (
    id INT PRIMARY KEY,
    last VARCHAR(20),
    first VARCHAR(20),
    sex VARCHAR(6),
    dob DATE,
    dod DATE
);

CREATE TABLE MovieGenre (
    mid INT,
    genre VARCHAR(20)
);

CREATE TABLE MovieActor (
    mid INT,
    aid INT,
    role VARCHAR(50)
);

CREATE TABLE Review (
    name VARCHAR(20),
    time DATETIME,
    mid INT,
    rating INT,
    comment TEXT
);

