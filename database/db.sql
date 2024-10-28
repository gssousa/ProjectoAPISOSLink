CREATE TABLE `user` (
    `UserID` INT(255) NOT NULL AUTO_INCREMENT,
    `Username` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `UserPassword` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `UserEmail` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL ,
    `UserCreationTimestamp` DATE NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`UserID`), UNIQUE (`Username`), UNIQUE (`UserEmail`)) ENGINE = InnoDB;