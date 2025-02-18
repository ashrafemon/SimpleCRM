<script setup lang="ts">
import { LeadTypes, RegularStatuses } from '@/Constants/options';
import { createLead, fetchLead, updateLead } from '@/States/Actions/Leads';
import { validateError } from '@/Utils/helper';
import { useMutation, useQuery } from '@tanstack/vue-query';
import { useMessage } from 'naive-ui';
import Validator from 'Validator';
import { PropType, ref, watch } from 'vue';
import LoadingSkeleton from '../UI/LoadingSkeleton.vue';

const { payload, closer, refetch } = defineProps({
    payload: {
        type: String as PropType<string | null>,
        default: null,
    },
    closer: {
        type: Function,
        default: () => { }
    },
    refetch: {
        type: Function,
        default: () => { }
    },
})

const { isFetching, isError, data, error } = useQuery({
    queryKey: ['lead'],
    queryFn: () => fetchLead(payload ?? ''),
    enabled: !!payload
})

const message = useMessage()
const form = ref<{ [key: string]: string }>({
    type: 'PERSON',
    name: '',
    email: '',
    phone: '',
    address: '',
    status: 'active',
})


const errors = ref<{ [key: string]: { text: string, show: boolean } }>({
    type: { show: false, text: '' },
    name: { show: false, text: '' },
    email: { show: false, text: '' },
    phone: { show: false, text: '' },
    address: { show: false, text: '' },
    status: { show: false, text: '' },
})

const errorFieldReset = (key: string) => {
    errors.value[key] = { text: '', show: false }
}

const successCallback = (res: any) => {
    message.success(res.message);
    refetch()
    closer()
}

const errorCallback = (error: any) => {
    const { data } = error;

    if (data?.status === 'validateError') {
        const vErrors = data?.data ?? {};
        errors.value = { ...errors.value, ...validateError(vErrors) }
    } else {
        message.error(data?.message)
    }
}

const { isPending: addDocPending, mutate: addDocMutate } = useMutation({
    mutationFn: (body: typeof form.value) => createLead(body),
    onSuccess: (data) => successCallback(data),
    onError: (error) => errorCallback(error)
})
const { isPending: updateDocPending, mutate: updateDocMutate } = useMutation({
    mutationFn: (body: typeof form.value) => updateLead(payload ?? '', body),
    onSuccess: (data) => successCallback(data),
    onError: (error) => errorCallback(error)
})

const submitHandler = () => {
    const validate = Validator.make(form.value, {
        name: 'required|min:3',
        email: 'required|email',
        phone: 'sometimes',
        address: 'sometimes',
        type: 'required|in:COMPANY,PERSON',
        status: 'required|in:active,inactive',
    });

    if (validate.fails()) {
        errors.value = { ...errors.value, ...validateError(validate.getErrors()) };
        return;
    }

    payload ? updateDocMutate(form.value) : addDocMutate(form.value)
}

watch(data, function () {
    const fDoc = data.value
    if (fDoc?.status === 'success') {
        if (fDoc?.data && Object.keys(fDoc?.data).length) {
            const doc = { ...form.value }
            Object.keys(fDoc.data).forEach((key) => {
                if (fDoc.data[key] !== null) {
                    doc[key] = fDoc.data[key]
                }
            })
            form.value = doc
        }
    }
})
</script>

<template>
    <template v-if="isFetching || isError">
        <LoadingSkeleton :isLoading="isFetching" :isError="isError" :error="error?.message" />
    </template>
    <template v-else>
        <form @submit.prevent="submitHandler">
            <n-form-item label="Type">
                <n-flex vertical class="w-full">
                    <n-select v-model:value="form.type" @blur="() => errorFieldReset('type')" :options="LeadTypes"
                        placeholder="Ex. Company" />
                    <p v-if="errors.type.show" class="text-xs font-medium text-red-500">{{ errors.type.text }}</p>
                </n-flex>
            </n-form-item>
            <n-form-item label="Name">
                <n-flex vertical class="w-full">
                    <n-input v-model:value="form.name" @blur="() => errorFieldReset('name')"
                        placeholder="Ex. Ashraf Emon" />
                    <p v-if="errors.name.show" class="text-xs font-medium text-red-500">{{ errors.name.text }}</p>
                </n-flex>
            </n-form-item>
            <n-form-item label="Email">
                <n-flex vertical class="w-full">
                    <n-input v-model:value="form.email" @blur="() => errorFieldReset('email')"
                        placeholder="Ex. ashraf@example.com" />
                    <p v-if="errors.email.show" class="text-xs font-medium text-red-500">{{ errors.email.text }}</p>
                </n-flex>
            </n-form-item>
            <n-form-item label="Phone">
                <n-flex vertical class="w-full">
                    <n-input v-model:value="form.phone" @blur="() => errorFieldReset('phone')"
                        placeholder="Ex. 01900000550" />
                    <p v-if="errors.phone.show" class="text-xs font-medium text-red-500">{{ errors.phone.text }}</p>
                </n-flex>
            </n-form-item>
            <n-form-item label="Address">
                <n-flex vertical class="w-full">
                    <n-input v-model:value="form.address" @blur="() => errorFieldReset('address')"
                        placeholder="Ex. Street Address" />
                    <p v-if="errors.address.show" class="text-xs font-medium text-red-500">{{ errors.address.text }}</p>
                </n-flex>
            </n-form-item>
            <n-form-item label="Status">
                <n-flex vertical class="w-full">
                    <n-select v-model:value="form.status" @blur="() => errorFieldReset('status')"
                        :options="RegularStatuses" placeholder="Ex. Active" />
                    <p v-if="errors.status.show" class="text-xs font-medium text-red-500">{{ errors.status.text }}</p>
                </n-flex>
            </n-form-item>
            <n-flex>
                <n-button :loading="addDocPending || updateDocPending" type="info" class="text-base"
                    attrType="submit">Save</n-button>
                <n-button :loading="addDocPending || updateDocPending" type="error" class="text-base"
                    @click="closer">Close</n-button>
            </n-flex>
        </form>
    </template>
</template>
