CREATE TABLE experiment (
       id INT NOT NULL AUTO_INCREMENT
     , name VARCHAR(255) NOT NULL
     , description TEXT
     , url VARCHAR(255) NOT NULL
     , PRIMARY KEY (id)
);

CREATE TABLE visitor (
       id CHAR(36) BINARY NOT NULL
     , ipv4 VARCHAR(16)
     , user_agent VARCHAR(1024)
     , created INT NOT NULL
     , UNIQUE token (id)
     , PRIMARY KEY (id)
);

CREATE TABLE event_type (
       id INT NOT NULL AUTO_INCREMENT
     , name VARCHAR(255) NOT NULL
     , token VARCHAR(32) NOT NULL
     , PRIMARY KEY (id)
);

CREATE TABLE client (
       id CHAR(32) BINARY NOT NULL
     , name VARCHAR(255) NOT NULL
     , secret CHAR(32) BINARY NOT NULL
     , PRIMARY KEY (id)
);

CREATE TABLE user (
       id INT NOT NULL AUTO_INCREMENT
     , name VARCHAR(255) NOT NULL
     , email VARCHAR(255) NOT NULL
     , password CHAR(128) NOT NULL
     , salt CHAR(16) NOT NULL
     , superuser BOOLEAN NOT NULL DEFAULT 0
     , UNIQUE UQ_user_1 (email)
     , PRIMARY KEY (id)
);

CREATE TABLE facet (
       id INT NOT NULL AUTO_INCREMENT
     , experiment_id INT NOT NULL
     , external_id INT NOT NULL
     , name VARCHAR(255) NOT NULL
     , static BOOL NOT NULL DEFAULT 0
     , PRIMARY KEY (id)
     , INDEX (experiment_id)
     , CONSTRAINT FK_facet_1 FOREIGN KEY (experiment_id)
                  REFERENCES experiment (id)
);

CREATE TABLE facet_value (
       id INT NOT NULL AUTO_INCREMENT
     , facet_id INT NOT NULL
     , external_id INT NOT NULL
     , name VARCHAR(255) NOT NULL
     , lft INT NOT NULL
     , rgt INT NOT NULL
     , root INT NOT NULL
     , PRIMARY KEY (id)
     , INDEX (facet_id)
     , CONSTRAINT FK_facet_value_1 FOREIGN KEY (facet_id)
                  REFERENCES facet (id)
);

CREATE TABLE event (
       id INT NOT NULL AUTO_INCREMENT
     , event_type_id INT NOT NULL
     , experiment_id INT NOT NULL
     , visitor_id CHAR(36) BINARY NOT NULL
     , facet_id INT
     , facet_value_id INT
     , timestamp INT NOT NULL
     , result_set_size INT
     , PRIMARY KEY (id)
     , INDEX (experiment_id)
     , CONSTRAINT FK_event_1 FOREIGN KEY (experiment_id)
                  REFERENCES experiment (id)
     , INDEX (facet_value_id)
     , CONSTRAINT FK_event_3 FOREIGN KEY (facet_value_id)
                  REFERENCES facet_value (id)
     , INDEX (event_type_id)
     , CONSTRAINT FK_event_4 FOREIGN KEY (event_type_id)
                  REFERENCES event_type (id)
     , INDEX (visitor_id)
     , CONSTRAINT FK_event_2 FOREIGN KEY (visitor_id)
                  REFERENCES visitor (id)
     , INDEX (facet_id)
     , CONSTRAINT FK_event_5 FOREIGN KEY (facet_id)
                  REFERENCES facet (id)
);

CREATE TABLE former_choice (
       id INT NOT NULL AUTO_INCREMENT
     , event_id INT NOT NULL
     , facet_id INT NOT NULL
     , facet_value_id INT NOT NULL
     , PRIMARY KEY (id)
     , INDEX (event_id)
     , CONSTRAINT FK_former_choice_1 FOREIGN KEY (event_id)
                  REFERENCES event (id)
     , INDEX (facet_value_id)
     , CONSTRAINT FK_former_choice_2 FOREIGN KEY (facet_value_id)
                  REFERENCES facet_value (id)
     , INDEX (facet_id)
     , CONSTRAINT FK_former_choice_3 FOREIGN KEY (facet_id)
                  REFERENCES facet (id)
);

CREATE TABLE client_binding (
       client_id CHAR(32) BINARY NOT NULL
     , experiment_id INT NOT NULL
     , PRIMARY KEY (client_id, experiment_id)
     , INDEX (client_id)
     , CONSTRAINT FK_client_binding_1 FOREIGN KEY (client_id)
                  REFERENCES client (id)
     , INDEX (experiment_id)
     , CONSTRAINT FK_client_binding_2 FOREIGN KEY (experiment_id)
                  REFERENCES experiment (id)
);

CREATE TABLE user_binding (
       user_id INT NOT NULL
     , experiment_id INT NOT NULL
     , role VARCHAR(32) NOT NULL
     , PRIMARY KEY (user_id, experiment_id)
     , INDEX (experiment_id)
     , CONSTRAINT FK_user_binding_2 FOREIGN KEY (experiment_id)
                  REFERENCES experiment (id)
     , INDEX (user_id)
     , CONSTRAINT FK_user_binding_1 FOREIGN KEY (user_id)
                  REFERENCES user (id)
);

CREATE TABLE facet_position (
       id INT NOT NULL AUTO_INCREMENT
     , visitor_id CHAR(36) BINARY NOT NULL
     , experiment_id INT NOT NULL
     , facet_id INT NOT NULL
     , position INT NOT NULL
     , UNIQUE UQ_facet_position_1 (visitor_id, experiment_id, facet_id)
     , PRIMARY KEY (id)
     , INDEX (experiment_id)
     , CONSTRAINT FK_facet_position_1 FOREIGN KEY (experiment_id)
                  REFERENCES experiment (id)
     , INDEX (facet_id)
     , CONSTRAINT FK_facet_position_3 FOREIGN KEY (facet_id)
                  REFERENCES facet (id)
     , INDEX (visitor_id)
     , CONSTRAINT FK_facet_position_2 FOREIGN KEY (visitor_id)
                  REFERENCES visitor (id)
);

