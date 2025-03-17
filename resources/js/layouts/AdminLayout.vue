<script setup lang="ts">
import Sidebar from "@/components/admin/Sidebar.vue";
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { MenuUnfoldOutlined, MenuFoldOutlined } from '@ant-design/icons-vue';
import { useFlashMessages } from '@/composables/useFlashMessages';
useFlashMessages();

// Sidebar state
const collapsed = ref(false);

</script>

<template>
    <Head title="Dashboard" />
    <a-layout>
        <!-- Sidebar Component -->
        <Sidebar :collapsed="collapsed" />

        <!-- Main Layout -->
        <a-layout :style="{ height: '100vh' }">
            <!-- Header -->
            <a-layout-header class="header">
                <menu-unfold-outlined v-if="collapsed" class="trigger" @click="collapsed = false" />
                <menu-fold-outlined v-else class="trigger" @click="collapsed = true" />
            </a-layout-header>

            <!-- Content Area -->
            <a-layout-content class="content">
                <slot />
            </a-layout-content>
        </a-layout>
    </a-layout>
</template>

<style scoped>
/* You can add styles here if needed */
</style>
