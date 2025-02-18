<template>
    <slot />
</template>

<script lang="ts" setup>
import { getQueryString } from '@/Utils/helper';
import { router, usePage } from '@inertiajs/vue3';
import Cookies from 'js-cookie';
import { jwtDecode } from 'jwt-decode';
import moment from 'moment';
import { onMounted, onUnmounted, provide, ref, watch } from 'vue';

const page = usePage();
const isAuthenticate = ref(false);
const currentUser = ref({});
const isLoading = ref(true);
const token = ref<string | null>(null);

provide('isAuthenticate', isAuthenticate)
provide('currentUser', currentUser)

let cookieWatcher: number | undefined = undefined;

const decoder = () => {
    token.value = Cookies.get("token") ?? null;
    if (!token.value) {
        if (!page.url.includes("/login")) {
            router.visit(`/login?redirect=${page.url}`, {
                replace: true,
            });
        }
        isLoading.value = false;
        return;
    }

    const decode = jwtDecode(token.value);
    const exp = decode?.exp
        ? moment.unix(decode.exp).diff(moment(), "minutes")
        : -1;
    if (exp <= 0) {
        Cookies.remove("token");
        if (!page.url.includes("/login")) {
            router.visit(`/login?redirect=${page.url}`, {
                replace: true,
            });
        }
        isLoading.value = false;
        return;
    }

    isAuthenticate.value = true;
    currentUser.value = decode;
    isLoading.value = false;

    if (page.url.includes("/login")) {
        const queryString = getQueryString();
        router.visit(queryString.redirect ?? "/dashboard", {
            replace: true,
        });
    }

    cookieWatcher = setInterval(() => {
        const currentToken = Cookies.get("token") ?? null;
        if (currentToken !== token.value) {
            token.value = currentToken;
        }
    }, 1000);
}

onMounted(() => {
    decoder()
});

onUnmounted(() => {
    isAuthenticate.value = false;
    currentUser.value = {};
    isLoading.value = true;

    if (cookieWatcher) {
        clearInterval(cookieWatcher);
    }
})

watch(token, () => {
    decoder()
})
</script>
