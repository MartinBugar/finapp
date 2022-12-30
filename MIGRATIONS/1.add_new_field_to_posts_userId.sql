ALTER TABLE posts
    add column userID bigint unsigned NOT NULL;

Alter Table posts
    add constraint posts_userid_fk
        FOREIGN KEY (userID) REFERENCES users (id)
