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

function get_user_name() {
    return isset($_SESSION['username']) ? $_SESSION['username'] : false;
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
