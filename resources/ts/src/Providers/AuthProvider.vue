<template>
    <slot />
</template>

<script lang="ts" setup>
import { getQueryString } from '@/Utils/helper';
import { router, usePage } from '@inertiajs/vue3';
import useAuthStore from '../States/Stores/authStore';
import Cookies from 'js-cookie';
import { jwtDecode } from 'jwt-decode';
import moment from 'moment';
import { onMounted } from 'vue';

const authStore = useAuthStore();
const page = usePage();

const checkAuth = () => {
    const cookieToken = Cookies.get('token');
    if (!cookieToken) {
        handleUnauthenticated();
        return;
    }

    if (!cookieToken) {
        handleUnauthenticated();
        return;
    }

    const decode = jwtDecode(cookieToken);
    const exp = decode?.exp
        ? moment.unix(decode.exp).diff(moment(), "minutes")
        : -1;

    if (exp <= 0) {
        handleUnauthenticated();
        return;
    }

    authStore.setAuth({ isAuthenticated: true, currentUser: decode, token: cookieToken });

    if (page.url.includes("/login")) {
        const queryString = getQueryString();
        router.visit(queryString.redirect ?? "/dashboard", {
            replace: true,
        });
    }
}

const handleUnauthenticated = () => {
    Cookies.remove("token");
    authStore.resetAuth();
    if (!page.url.includes("/login")) {
        router.visit(`/login?redirect=${page.url}`, {
            replace: true,
        });
    }
}

onMounted(() => {
    checkAuth();
});
</script>
