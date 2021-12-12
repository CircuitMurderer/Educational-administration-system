-- -----------------------------------------
-- CircuitMurderer designed, All rights reserved.
-- -----------------------------------------

SET NAMES utf8;

DROP DATABASE IF EXISTS 'edu';

CREATE DATABASE 'edu';
USE 'edu';

DROP TABLE IF EXISTS `chose_course`;
DROP TABLE IF EXISTS `course`;
DROP TABLE IF EXISTS `teacher`;
DROP TABLE IF EXISTS `student`;
DROP TABLE IF EXISTS `user`;

CREATE TABLE `student` (
    sid char(10) PRIMARY KEY NOT NULL,
    s_name char(20) NOT NULL,
    s_dept char(20) NOT NULL,
    s_sex char(6) NOT NULL CHECK (s_sex in ('male', 'female'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `teacher` (
    tid char(8) PRIMARY KEY NOT NULL,
    t_name char(20) NOT NULL,
    t_dept char(20) NOT NULL,
    t_sex char(6) NOT NULL CHECK (t_sex in ('male', 'female'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `course` (
    cid char(6) PRIMARY KEY NOT NULL,
    c_name char(20) NOT NULL,
    tid char(8) NOT NULL,
    credit smallint(1) NOT NULL ,
    FOREIGN KEY (tid) REFERENCES teacher(tid) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `chose_course` (
    sid char(10) NOT NULL,
    cid char(6) NOT NULL,
    score smallint(3) DEFAULT NULL CHECK (score IS NULL OR score BETWEEN 0 AND 100),
    FOREIGN KEY (sid) REFERENCES student(sid) ON DELETE CASCADE,
    FOREIGN KEY (cid) REFERENCES course(cid) ON DELETE CASCADE,
    PRIMARY KEY (sid, cid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `user` (
    uid char(10) PRIMARY KEY NOT NULL,
    pwd char(40) NOT NULL,
    mode char(2) NOT NULL CHECK (mode in ('SD', 'TC', 'SU'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO user VALUES ('superuser', 'bd169ee16deeb74c32afee8495ea2f4811792a19', 'SU');


DROP USER IF EXISTS 'userTC'@'localhost';
DROP USER IF EXISTS 'userSD'@'localhost';

CREATE USER 'userTC'@'localhost' IDENTIFIED BY 'teacher';
CREATE USER 'userSD'@'localhost' IDENTIFIED BY 'student';

GRANT SELECT,INSERT,DELETE ON edu.course TO 'userTC'@'localhost' IDENTIFIED BY 'teacher';
GRANT SELECT,UPDATE ON edu.chose_course TO 'userTC'@'localhost' IDENTIFIED BY 'teacher';
GRANT SELECT ON edu.student TO 'userTC'@'localhost' IDENTIFIED BY 'teacher';
GRANT SELECT ON edu.teacher TO 'userTC'@'localhost' IDENTIFIED BY 'teacher';

GRANT SELECT ON edu.course TO 'userSD'@'localhost' IDENTIFIED BY 'student';
GRANT SELECT,INSERT,DELETE ON edu.chose_course TO 'userSD'@'localhost' IDENTIFIED BY 'student';
GRANT SELECT ON edu.student TO 'userSD'@'localhost' IDENTIFIED BY 'student';
GRANT SELECT ON edu.teacher TO 'userSD'@'localhost' IDENTIFIED BY 'student';

