<script setup lang="ts">
import { LeadAssignmentStatuses } from '@/Constants/options';
import { createLeadAssignment, fetchLeadAssignment, updateLeadAssignment } from '@/States/Actions/LeadAssignments';
import { fetchLeads } from '@/States/Actions/Leads';
import { fetchUsers } from '@/States/Actions/Users';
import { validateError } from '@/Utils/helper';
import { useMutation, useQuery } from '@tanstack/vue-query';
import { useMessage } from 'naive-ui';
import Validator from 'Validator';
import { computed, PropType, ref, watch, onMounted, onUpdated } from 'vue';
import LoadingSkeleton from '../UI/LoadingSkeleton.vue';

const { payload, leadId, userId, closer, refetch } = defineProps({
    payload: {
        type: String as PropType<string | null>,
        default: null,
    },
    leadId: {
        type: String as PropType<string | null>,
        default: null,
    },
    userId: {
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
    queryFn: () => fetchLeadAssignment(payload ?? ''),
    enabled: !!payload
})

const { data: counselors } = useQuery({
    queryKey: ['counselors'],
    queryFn: () => fetchUsers(`get_all=1&role=COUNSELOR&status=active&fields=id,name`),
    enabled: !userId
})

const { data: leads } = useQuery({
    queryKey: ['leads'],
    queryFn: () => fetchLeads(`get_all=1&status=active&fields=id,name`),
    enabled: !leadId
})

const message = useMessage()
const form = ref<{ [key: string]: string | any }>({
    lead_id: leadId,
    user_id: userId,
    status: 'ASSIGNED',
    app_name: ''
})

const errors = ref<{ [key: string]: { text: string, show: boolean } }>({
    lead_id: { show: false, text: '' },
    user_id: { show: false, text: '' },
    status: { show: false, text: '' },
    app_name: { show: false, text: '' },
})

const formRules = ref({
    lead_id: 'required',
    user_id: 'required',
    status: 'required|in:ASSIGNED,IN_PROGRESS,BAD_TIMING,NOT_INTERESTED,NOT_QUALIFIED,CONVERTED',
    app_name: 'required_if:status,CONVERTED',
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
    mutationFn: (body: typeof form.value) => createLeadAssignment(body),
    onSuccess: (data) => successCallback(data),
    onError: (error) => errorCallback(error)
})
const { isPending: updateDocPending, mutate: updateDocMutate } = useMutation({
    mutationFn: (body: typeof form.value) => updateLeadAssignment(payload ?? '', body),
    onSuccess: (data) => successCallback(data),
    onError: (error) => errorCallback(error)
})

const submitHandler = () => {
    const validate = Validator.make(form.value, formRules.value);

    if (validate.fails()) {
        errors.value = { ...errors.value, ...validateError(validate.getErrors()) };
        return;
    }

    payload ? updateDocMutate(form.value) : addDocMutate(form.value)
}

const leadOptions = computed(() => {
    return leads?.value?.data?.map((item: { name: string, id: string }) => ({ label: item.name, value: item.id }))
})

const counselorOptions = computed(() => {
    return counselors?.value?.data?.map((item: { name: string, id: string }) => ({ label: item.name, value: item.id }))
})

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
            <template v-if="!leadId">
                <n-form-item label="Lead">
                    <n-flex vertical class="w-full">
                        <n-select v-model:value="form.lead_id" @blur="() => errorFieldReset('lead_id')"
                            :options="leadOptions" placeholder="Ex. Company" />
                        <p v-if="errors.lead_id.show" class="text-xs font-medium text-red-500">{{ errors.lead_id.text }}
                        </p>
                    </n-flex>
                </n-form-item>
            </template>
            <template v-if="!userId">
                <n-form-item label="Counselor">
                    <n-flex vertical class="w-full">
                        <n-select v-model:value="form.user_id" @blur="() => errorFieldReset('user_id')"
                            :options="counselorOptions" placeholder="Ex. User" />
                        <p v-if="errors.user_id.show" class="text-xs font-medium text-red-500">{{ errors.user_id.text }}
                        </p>
                    </n-flex>
                </n-form-item>
            </template>
            <n-form-item label="Status">
                <n-flex vertical class="w-full">
                    <n-select v-model:value="form.status" @blur="() => errorFieldReset('status')"
                        :options="LeadAssignmentStatuses" placeholder="Ex. Assigned" />
                    <p v-if="errors.status.show" class="text-xs font-medium text-red-500">{{ errors.status.text }}</p>
                </n-flex>
            </n-form-item>
            <template v-if="form.status === 'CONVERTED'">
                <n-form-item label="App Name">
                    <n-flex vertical class="w-full">
                        <n-input v-model:value="form.app_name" @blur="() => errorFieldReset('app_name')"
                            placeholder="Ex. Web Development" />
                        <p v-if="errors.app_name.show" class="text-xs font-medium text-red-500">{{ errors.app_name.text
                            }}</p>
                    </n-flex>
                </n-form-item>
            </template>
            <n-flex>
                <n-button :loading="addDocPending || updateDocPending" type="info" class="text-base"
                    attrType="submit">Save</n-button>
                <n-button :loading="addDocPending || updateDocPending" type="error" class="text-base"
                    @click="closer">Close</n-button>
            </n-flex>
        </form>
    </template>
</template>
