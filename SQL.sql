CREATE TABLE stippers_users (
user_id BIGINT UNSIGNED PRIMARY KEY,
email VARCHAR(50) NOT NULL,
first_name VARCHAR(30) NOT NULL,
last_name VARCHAR(30) NOT NULL,
password_hash CHAR(64) NOT NULL,
password_salt CHAR(36) NOT NULL,
balance DECIMAL(5) NOT NULL DEFAULT 0,
phone VARCHAR(14) NOT NULL DEFAULT "",
date_of_birth DATE NOT NULL,
street VARCHAR(30) NOT NULL,
house_number VARCHAR(4) NOT NULL,
city VARCHAR(30) NOT NULL,
postal_code VARCHAR(6) NOT NULL,
country VARCHAR(30) NOT NULL,
is_admin BIT(1) NOT NULL DEFAULT 0,
is_hint_manager BIT(1) NOT NULL DEFAULT 0,
is_user_manager BIT(1) NOT NULL DEFAULT 0,
is_browser_manager BIT(1) NOT NULL DEFAULT 0,
is_money_manager BIT(1) NOT NULL DEFAULT 0,
creation_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
CONSTRAINT stippers_users_email_uq UNIQUE (email)
)
CHARACTER SET UTF8
ENGINE = InnoDB;

CREATE TABLE stippers_user_card_year (
user BIGINT UNSIGNED,
card INT UNSIGNED NOT NULL,
membership_year YEAR(4),
CONSTRAINT stippers_user_card_year2_user_fk FOREIGN KEY (user) REFERENCES stippers_users(user_id) ON DELETE CASCADE,
CONSTRAINT stippers_user_card_year2_card_max_ck CHECK (card < 99999999),
CONSTRAINT stippers_user_card_year2_pk PRIMARY KEY (user, membership_year),
CONSTRAINT stippers_user_card_year2_card_year_uq UNIQUE (card, membership_year)
)
CHARACTER SET UTF8
ENGINE = InnoDB;

CREATE TABLE stippers_user_year_gadgets (
user BIGINT UNSIGNED,
membership_year YEAR(4),
sweater BIT(1) NOT NULL DEFAULT 0,
sweater_size TINYINT,
tshirt BIT(1) NOT NULL DEFAULT 0,
tshirt_size TINYINT,
bartender_info BIT(1) NOT NULL DEFAULT 0,
CONSTRAINT stippers_user_year_gadgets_fk FOREIGN KEY (user) REFERENCES stippers_users(user_id) ON DELETE CASCADE,
CONSTRAINT stippers_user_year_pk PRIMARY KEY (user, membership_year)
)
CHARACTER SET UTF8
ENGINE = InnoDB;

CREATE TABLE stippers_check_ins (
time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
user BIGINT UNSIGNED,
CONSTRAINT check_ins_pk PRIMARY KEY (time,user),
CONSTRAINT check_ins_user_fk FOREIGN KEY (user) REFERENCES stippers_users(user_id) ON DELETE CASCADE
)
CHARACTER SET UTF8
ENGINE = InnoDB;

CREATE TABLE stippers_browsers (
browser_id SERIAL,
uuid CHAR(36) NOT NULL,
name CHAR(30) NOT NULL,
can_add_renew_users BIT(1) NOT NULL DEFAULT 0,
can_check_in BIT(1) NOT NULL DEFAULT 0,
is_cash_register BIT(1) NOT NULL DEFAULT 0,
CONSTRAINT stippers_browsers_uuid_uq UNIQUE (uuid),
CONSTRAINT stippers_browsers_name_uq UNIQUE (name)
)
CHARACTER SET UTF8
ENGINE = InnoDB;

CREATE TABLE stippers_money_transactions (
transaction_id SERIAL,
affected_user BIGINT UNSIGNED,
bal_before DECIMAL(5) NOT NULL,
incr_money DECIMAL(5) NOT NULL,
decr_money DECIMAL(5) NOT NULL,
discount_perc DECIMAL(5) NOT NULL,
time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
executing_browser_name VARCHAR(30),
executing_user BIGINT UNSIGNED,
CONSTRAINT money_transactions_affected_user_fk FOREIGN KEY (affected_user) REFERENCES stippers_users(user_id) ON DELETE SET NULL,
CONSTRAINT money_transactions_executing_user_fk FOREIGN KEY (executing_user) REFERENCES stippers_users(user_id) ON DELETE SET NULL
)
CHARACTER SET UTF8
ENGINE = InnoDB;

CREATE TABLE stippers_hints (
hint_number BIGINT UNSIGNED PRIMARY KEY,
hint_text TEXT NOT NULL,
hint_type TINYINT UNSIGNED NOT NULL,
min_check_in_time TIMESTAMP NOT NULL,
max_check_in_time TIMESTAMP NOT NULL
)
CHARACTER SET UTF8
ENGINE InnoDB;

CREATE TABLE stippers_hint_images (
image_id BIGINT UNSIGNED PRIMARY KEY,
filename CHAR(40) NOT NULL
)
CHARACTER SET UTF8
ENGINE InnoDB;

CREATE TABLE stippers_chat (
message_id BIGINT UNSIGNED PRIMARY KEY,
user BIGINT UNSIGNED NOT NULL,
text VARCHAR(150) NOT NULL,
message_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
CONSTRAINT chat_user_fk FOREIGN KEY (user) REFERENCES stippers_users(user_id)
)
CHARACTER SET UTF8
ENGINE InnoDB;

CREATE TABLE stippers_weekly_winners (
start_of_week DATE,
user BIGINT UNSIGNED NOT NULL,
has_collected_prize BIT(1) NOT NULL DEFAULT 0,
CONSTRAINT weekly_winner_pk PRIMARY KEY (start_of_week)
)
CHARACTER SET UTF8
ENGINE = InnoDB;

DELIMITER ;;
CREATE PROCEDURE stippers_create_sequence (p_name VARCHAR(100))
BEGIN
	CREATE TABLE IF NOT EXISTS stippers_sequences (
		name VARCHAR(100) PRIMARY KEY,
		next_val BIGINT UNSIGNED NOT NULL DEFAULT 1
	)
	CHARACTER SET UTF8
	ENGINE InnoDB;
	INSERT INTO stippers_sequences (name) VALUES (p_name);  
END;;
DELIMITER ;

DELIMITER ;;
CREATE FUNCTION stippers_nextval (p_seq_name VARCHAR(100))
RETURNS BIGINT UNSIGNED
BEGIN
	UPDATE stippers_sequences
	SET next_val = (@cur_val := next_val)+1
	WHERE name = p_seq_name;

    RETURN @cur_val;
END;;
DELIMITER ;

DELIMITER ;;
CREATE PROCEDURE stippers_drop_sequence (p_name VARCHAR(100))
BEGIN
     DELETE FROM stippers_sequences WHERE name = p_name;  
END;;
DELIMITER ;

CALL stippers_create_sequence('stippers_users_seq');
CALL stippers_create_sequence('stippers_hints_seq');
CALL stippers_create_sequence('stippers_hint_images_seq');
CALL stippers_create_sequence('stippers_chat_seq');
CALL stippers_create_sequence('stippers_weekly_winner_seq');