<template>
    <n-config-provider>
        <n-global-style />
        <n-message-provider>
            <slot />
        </n-message-provider>
    </n-config-provider>
</template>

<script setup lang="ts">
import { NConfigProvider, NGlobalStyle, NMessageProvider } from 'naive-ui';
</script>
