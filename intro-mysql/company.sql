CREATE DATABASE company; -- database olusturduk

CREATE TABLE employee (
employee_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
first_name VARCHAR(50) NOT NULL,
last_name VARCHAR(50) NOT NULL,
email VARCHAR(50) UNIQUE NOT NULL,
salary DECIMAL(8,2) NOT NULL
) -- employee tablosu olusturduk   
/*
emplooyee_id yi unsigned yani eksi deger almayacak derecede ve her bir olusturmada artacak sekilde olusturduk ve primary key ozelligi verdik
email i unique sectik ki ayni email adresinden tekrardan olusturulmasin.
*/


INSERT INTO employee(
    first_name, 
    last_name,
    email,
    salary
) VALUES ('Ali' , 'Yilmaz' , 'okethis@gmail.com' , 10.87);

INSERT INTO employee(
    first_name, 
    last_name,
    email,
    salary
) VALUES ('Furkan' , 'Tortop' , 'mft@gmail.com' , 11.87);

INSERT INTO employee(
    first_name, 
    last_name,
    email,
    salary
) VALUES ('Serkus' , 'Yilmaz' , 'serkus@gmail.com' , 20);


INSERT INTO employee(
    first_name, 
    last_name,
    email,
    salary
) VALUES ('Tester' , 'Yilmaz' , 'tester@gmail.com' , 33);

INSERT INTO employee(
    first_name, 
    last_name,
    email,
    salary
) VALUES ('Ogun' , 'Baysal' , 'baysal@gmail.com' , 24.866);

-- sample datas eklendi


SELECT emp.first_name , emp.last_name FROM employee as emp -- tum calisanlarin isim soyisimleri getirildi

SELECT SUBSTRING(emp.first_name,1, 1) as ISIM , SUBSTRING(emp.last_name, 1, 1) as SOYISIM FROM employee as emp
-- yukardaki sorgu ile her bis calisanin isim ve soy isimlerinin bas harfleri getirildi


SELECT concat(SUBSTRING(emp.first_name,1, 1), '...', RIGHT(emp.first_name, 1) ) as ISIM , concat(SUBSTRING(emp.last_name,1, 1), '...', RIGHT(emp.last_name, 1) ) as SOYISIM FROM employee as emp
-- yukardaki sorgu ile her bir calisanin isim ve soyismindeki bas harfleri getirdik

UPDATE employee as emp SET salary = 29 WHERE emp.email = 'okethis@gmail.com' --okethis email icin salary update edildi
-- eger olmayan bir email update etmek istersek hata donmez ama 0 row effected olur
UPDATE employee as emp SET employee_id = 1 WHERE emp.employee_id = 29 -- primary key update edilebilir
UPDATE employee as emp SET emp.last_name = null WHERE emp.employee_id = 1 -- not null oldugundan calismaz

DELETE FROM employee WHERE email = 'tester@gmail.com' -- delete tester email

DROP TABLE Customers; -- table drop eder.

CREATE UNIQUE INDEX email_index
ON employee (email); -- email icin index olusturuldu

CREATE TABLE department (
    department_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    department_name VARCHAR(50) NOT NULL
)

ALTER TABLE employee
ADD COLUMN department_id INT(11) UNSIGNED;

ALTER TABLE employee ADD CONSTRAINT fk_department FOREIGN KEY (department_id) REFERENCES department(department_id);

-- bu adimda ikisini birlestirmek icin ilgili emp tablosune department id ekledim ve bunu diger tablo ile birlestirdim
START TRANSACTION;
SET autocommit = 1;
UPDATE employee as emp SET emp.salary = 888 WHERE employee_id = 1;
COMMIT;
ROLLBACK;
-- bu adimda transation baslatip update aliyoruz bunu commitliyoruz veya rollback yapiyoruz

/*
concurrency eszamanlilik olarak geciyor ve bir tablodaki erisimlerin esit sekilde gerceklestirmesini ve sorgularin esit sekilde calistirilmasi anlamina geliyor. bu yuzden bazi durumlarda cakismalar olabiliyor. Bunun onune gecmek icin isolationlar kullaniyor.
Isolationlar ise farkli cesitleri var, ornek olarak serilazible da sadece select e izin veriyor es zamanli calisan 2 transaction icin. 
Ornek bir senaryo olarak herhangi bir seyi ortak paylasan iki transaction da gecerli olabilir, bir hesap olabilir, bir urun olabilir, bir rezervasyon olabilir. Bu senaryolarda genel olarak bir seye erisimlerin kontrol edilmesi gerekiyor.
Osdeki semaphore mutex mantigi gibi ayni sekilde dbms de de concurrency ve isolation ayarlarinin ve erisimlerinin duzgun yapilmasi gerekiyor. Hatali bir durumda ayni masa icin 2 kisi rezervasyon yapabilir. 

*/