<template>
    <div class="w-full h-screen bg-gray-300 flex justify-center items-center">
        <n-space vertical align="center">
            <n-image width="100" :src="Images.logo"
                fallback-src="https://07akioni.oss-cn-beijing.aliyuncs.com/07akioni.jpeg" />

            <n-card size="huge" class="rounded-xl">
                <n-space vertical>
                    <n-flex vertical align="center" class="mb-8">
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

<script setup lang="ts">
import { Images } from '@/Constants/theme'
</script>
