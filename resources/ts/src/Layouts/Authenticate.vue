<template>
    <div class="w-full h-screen bg-gray-300 flex justify-center items-center">
        <n-space align="center" vertical>
            <n-image :src="Images.logo" fallback-src="https://07akioni.oss-cn-beijing.aliyuncs.com/07akioni.jpeg"
                width="100" />

            <n-card class="rounded-xl" size="huge">
                <n-space vertical>
                    <n-flex align="center" class="mb-8" vertical>
                        <h4 class="text-2xl font-semibold">Sign In Access</h4>
                        <p class="text-sm">
                            You must become a member to login and access the entire site
                        </p>
                    </n-flex>
                    <slot />
                </n-space>
            </n-card>
        </n-space>
    </div>
</template>

<script lang="ts" setup>
import { Images } from '@/Constants/theme';
</script>
