-- User with nbConnexion=0 AND last_login iS NULL
SELECT
    id,
    username,
    email,
    last_login,
    created_at,
    updated_at,
    nbConnexion
FROM `user`
WHERE nbConnexion = 0
AND last_login IS NULL
AND created_at < '2021-01-01';




