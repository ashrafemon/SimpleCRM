<template>

    <Head title="Login" />

    <form @submit.prevent="submitHandler">
        <n-form-item label="Email">
            <n-flex vertical class="w-full">
                <n-input v-model:value="form.email" @blur="() => errorFieldReset('email')" size="large"
                    placeholder="Ex. test@test.com" />
                <p v-if="errors.email.show" class="text-xs font-medium text-red-500">{{ errors.email.text }}</p>
            </n-flex>
        </n-form-item>
        <n-form-item label="Password">
            <n-flex vertical class="w-full">
                <n-input v-model:value="form.password" @blur="() => errorFieldReset('password')" size="large"
                    show-password-on="mousedown" type="password" placeholder="Ex. 123456" />
                <p v-if="errors.password.show" class="text-xs font-medium text-red-500">{{ errors.password.text }}
                </p>
            </n-flex>
        </n-form-item>
        <n-button :loading="loginPending" type="info" size="large" class="text-xl font-semibold" block
            attrType="submit">Sign In</n-button>
    </form>
</template>

<script setup lang="ts">
import Authenticate from '@/Layouts/Authenticate.vue';
import { login } from '@/States/Actions/Auth';
import { validateError } from '@/Utils/helper';
import { router } from '@inertiajs/core';
import { Head } from '@inertiajs/vue3';
import { useMutation } from '@tanstack/vue-query';
import Cookies from 'js-cookie';
import { useMessage } from 'naive-ui';
import Validator from 'Validator';
import { ref } from 'vue';
import { getQueryString } from '../../Utils/helper';

defineOptions({ layout: Authenticate })

const message = useMessage()
const form = ref({
    email: '',
    password: ''
})
const errors = ref<{ [key: string]: { text: string, show: boolean } }>({
    email: { text: '', show: false },
    password: { text: '', show: false }
})

const errorFieldReset = (key: string) => {
    errors.value[key] = { text: '', show: false }
}

const { isPending: loginPending, mutate: loginMutate } = useMutation({
    mutationFn: (body: typeof form.value) => login(body),
    onSuccess: (data) => {
        message.success(data.message);
        Cookies.set('token', data.data)
        const queryString = getQueryString();
        router.visit(queryString.redirect ?? "/dashboard", {
            replace: true,
        });
    },
    onError: (error: any) => {
        const { data } = error as { data?: any };
        if (data?.status === 'validateError') {
            const vErrors = data?.data ?? {};
            errors.value = { ...errors.value, ...validateError(vErrors) }
        } else {
            message.error(data?.message)
        }
    }
})

const submitHandler = () => {
    const validate = Validator.make(form.value, {
        email: 'required|email',
        password: 'required|min:6',
    });

    if (validate.fails()) {
        errors.value = { ...errors.value, ...validateError(validate.getErrors()) };
        return;
    }

    loginMutate(form.value)
}

</script>
