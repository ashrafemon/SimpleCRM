<template>

    <Head title="Dashboard" />

    <n-flex vertical>
        <Summaries />

        <template v-if="isAdmin">
            <CounselorStats />
        </template>
    </n-flex>
</template>

<script setup lang="ts">
import CounselorStats from '@/Components/Dashboard/CounselorStats.vue';
import Summaries from '@/Components/Dashboard/Summaries.vue';
import Panel from '@/Layouts/Panel.vue';
import useAuthStore from '@/States/Stores/authStore';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';

defineOptions({ layout: Panel })
const authStore = useAuthStore()
const isAdmin = computed(() => authStore.currentUser?.role === 'ADMIN')
</script>
