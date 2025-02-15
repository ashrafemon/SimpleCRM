<template>

    <Head title="Login" />
    <form action="">
        <n-flex vertical>
            <TextBox label="Email Address" placeholder="Email Address" size="large">
                <template #prefix>
                    <n-icon>
                        <Email />
                    </n-icon>
                </template>
            </TextBox>

            <TextBox label="Password" placeholder="Password" size="large" type="password" show-password-on="mousedown">
                <template #prefix>
                    <n-icon>
                        <Email />
                    </n-icon>
                </template>
            </TextBox>

            <div class="py-5 w-full">
                <n-button type="info" size="large" class="text-xl font-semibold" block @click="signInHandler">Sign
                    In</n-button>
            </div>
        </n-flex>

    </form>
</template>

<script setup lang="ts">
import Authenticate from '@/Layouts/Authenticate.vue';
import TextBox from '@/Components/UI/TextBox.vue';
import { Email } from '@vicons/carbon'
import { router } from '@inertiajs/core';
import { Head } from '@inertiajs/vue3';
defineOptions({ layout: Authenticate })

const signInHandler = () => {
    router.visit('/dashboard')
}
</script>
