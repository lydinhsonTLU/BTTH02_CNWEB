

    --THỐNG NHẤT ĐẶT TÊN CSDL LÀ 'BTTH02_CNWEB' GIỐNG TRONG FILE ConectDb.php


-- Tạo bảng users (chung)
CREATE TABLE users
(
    id       INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255) UNIQUE NOT NULL,
    email    VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255)        NOT NULL,
    fullname VARCHAR(255),
    role     INT                 NOT NULL DEFAULT 0 created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)

-- Tạo bảng categories
CREATE TABLE categories
(
    id          INT PRIMARY KEY AUTO_INCREMENT,
    name        VARCHAR(255) NOT NULL,
    description TEXT,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP
)

-- Tạo bảng courses
CREATE TABLE courses
(
    id             INT PRIMARY KEY AUTO_INCREMENT,
    title          VARCHAR(255) NOT NULL,
    description    TEXT,
    instructor_id  INT          NOT NULL,
    category_id    INT          NOT NULL,
    price          DECIMAL(10, 2) DEFAULT 0.00,
    duration_weeks INT,
    level          VARCHAR(50) image VARCHAR(255),
    created_at     DATETIME       DEFAULT CURRENT_TIMESTAMP,
    updated_at     DATETIME       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (instructor_id) REFERENCES users (id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE CASCADE
)

-- Tạo bảng enrollments (Học viên)
CREATE TABLE enrollments
(
    id            INT PRIMARY KEY AUTO_INCREMENT,
    course_id     INT NOT NULL,
    student_id    INT NOT NULL,
    enrolled_date DATETIME    DEFAULT CURRENT_TIMESTAMP,
    status        VARCHAR(50) DEFAULT 'active' progress INT DEFAULT 0
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES users (id) ON DELETE CASCADE,
    UNIQUE KEY unique_enrollment (course_id, student_id)
)

-- Tạo bảng lessons
CREATE TABLE lessons
(
    id         INT PRIMARY KEY AUTO_INCREMENT,
    course_id  INT          NOT NULL,
    title      VARCHAR(255) NOT NULL,
    content    LONGTEXT,
    video_url  VARCHAR(255),
    order      INT      DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses (id) ON DELETE CASCADE
)

-- Tạo bảng materials
CREATE TABLE materials
(
    id        INT PRIMARY KEY AUTO_INCREMENT,
    lesson_id INT          NOT NULL,
    filename  VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_type VARCHAR(50) uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (lesson_id) REFERENCES lessons (id) ON DELETE CASCADE
)

