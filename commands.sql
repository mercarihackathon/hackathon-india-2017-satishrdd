CREATE TABLE `user_data`( `id` INT NOT NULL AUTO_INCREMENT, `unique_id` INT NOT NULL, `credits` INT NOT NULL, `name` VARCHAR(60) NOT NULL, primary key (`id`,`unique_id`));
CREATE TABLE `storage_data`( `id` INT NOT NULL AUTO_INCREMENT,`unique_id` INT NOT NULL UNIQUE,`storage_power` INT NOT NULL ,primary key(`id`,`unique_id`));
insert into user_data(unique_id,credits,name) values(10,9,'test');
INSERT INTO storage_data(unique_id,storage_power) VALUES(5555,10);