drop schema if exists proj1  ;
create schema proj1;
use proj1;

CREATE TABLE schedule (
  scheduleid INT AUTO_INCREMENT PRIMARY KEY,
  sdate DATE,
  timeFrom datetime,
  timeTo datetime,
  numberFrom INT,
  numberTo INT,
  isRepeat BOOLEAN
);

CREATE TABLE note (
  noteid INT AUTO_INCREMENT PRIMARY KEY,
  time DATETIME,
  latitude FLOAT,
  longitude FLOAT,
  radius INT,
  content VARCHAR(2000),
  scheduleid INT,
  scope VARCHAR(60),
  allow_comment BOOLEAN,
  FOREIGN KEY (scheduleid) REFERENCES schedule(scheduleid) ON DELETE CASCADE
);


CREATE TABLE tag (
  tagid INT AUTO_INCREMENT PRIMARY KEY,
  tagname VARCHAR(255)
);

CREATE TABLE note2tag(
  noteid INT,
  tagid INT,
  FOREIGN KEY (noteid) REFERENCES note(noteid) ON DELETE CASCADE,
  FOREIGN KEY (tagid) REFERENCES tag(tagid) ON DELETE CASCADE
);

CREATE TABLE user(
  userid INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(60),
  username VARCHAR(60),
  pwd VARCHAR(60)
);

CREATE TABLE filter(
  userid INT,
  tagid INT,
  FOREIGN KEY (userid) REFERENCES user(userid) ON DELETE CASCADE,
  FOREIGN KEY (tagid) REFERENCES tag(tagid) ON DELETE CASCADE
);

CREATE TABLE friendship(
  userid INT,
  friendid INT,
  FOREIGN KEY (userid) REFERENCES  user(userid) ON DELETE CASCADE,
  FOREIGN KEY (friendid) REFERENCES user(userid) ON DELETE CASCADE
);

CREATE TABLE comments(
  commentid INT AUTO_INCREMENT PRIMARY KEY,
  commentTo INT,
  content VARCHAR(255),
  FOREIGN KEY (commentTo) REFERENCES  note(noteid) ON DELETE CASCADE
);

CREATE TABLE profile(
  userid INT,
  gender VARCHAR(20),
  birthday VARCHAR(60),
  description VARCHAR(200),
  FOREIGN KEY (userid) REFERENCES user(userid) ON DELETE CASCADE
);
