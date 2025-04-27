<?php
const ROLE_ADMIN = 'admin';
const ROLE_SUPER_ADMIN = 'superadmin';
const ROLE_USER = 'user';
const REGISTERED_ROLES = [ROLE_USER, ROLE_ADMIN, ROLE_SUPER_ADMIN];
const ROLE_ANON = 'anon';
function is_logged_in() {
    return is_allowed_user_role(REGISTERED_ROLES);
}
function is_allowed_user_role(array $allowlist): bool {
    return in_array(get_user_role(), $allowlist);
}

function get_user_id() {
    if (isset($_SESSION['user_id'])) {
        return $_SESSION['user_id'];
    };
    return false;
}

function get_user_role() {
    if (isset($_SESSION['role'])) {
        $role = $_SESSION['role'];
        if (in_array($role,REGISTERED_ROLES)) {
            return $role;
        } else {
            return ROLE_ANON;
        }
    } else return ROLE_ANON;
}
// 1. See assorted devices as an anonymous user; +
// 2. Login or register as an anonymous user; +
//     2.1 As a logged-in user I can log out; +

// 3. As a teacher I can make booking requests for devices;
// 4. As a teacher I can manage my own loans;

// 5. As an admin I can decline and approve booking requests submitted by teachers;
// 6. As an admin I can turn approved device bookings into device loans.
// 7. As an admin I can close device loans when device is returned. +
// 8. As an admin I can CRUD devices.
// 9. As an admin I can see a list of registered users.

// 10. As a superadmin I can remove users;
// 11. As a superadmin I can promote users to admins;
// 12. As a superadmin I can promote admins to superadmins;
// + all regular admin priviliges.
