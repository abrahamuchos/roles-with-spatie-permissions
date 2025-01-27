select * from users;

select r.id, r.name, r.guard_name, p.id, p.name
from roles r
join role_has_permissions rhp on r.id = rhp.role_id
join permissions p on rhp.permission_id = p.id
order by r.name;


select u.id, u.name, p.id, p.title, p.slug
    from users u
    join posts p on u.id = p.user_id
