<script setup lang="ts">
import Sidebar from "@/components/admin/Sidebar.vue";
import { Head } from '@inertiajs/vue3';
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { useFlashMessages } from '@/composables/useFlashMessages';
useFlashMessages();

// State for sidebar
const isMobile = ref(window.innerWidth <= 992);
const collapsed = ref(isMobile.value);

// Handle window resize
const handleResize = () => {
    isMobile.value = window.innerWidth <= 992;
    collapsed.value = isMobile.value;
};

// Close sidebar when clicking outside (on mobile)
const closeSidebar = (event: MouseEvent) => {
    if (isMobile.value && !event.target.closest('.sidebar, .trigger')) {
        collapsed.value = true;
    }
};

// Add event listeners
onMounted(() => {
    window.addEventListener("resize", handleResize);
    document.addEventListener("click", closeSidebar);
});

// Cleanup event listeners
onBeforeUnmount(() => {
    window.removeEventListener("resize", handleResize);
    document.removeEventListener("click", closeSidebar);
});
</script>

<template>
    <Head title="Dashboard" />
    <a-layout>
        <!-- Sidebar -->
        <div :class="['sidebar-wrapper', { 'overlay': isMobile && !collapsed }]">
            <Sidebar v-if="!collapsed" :collapsed="collapsed" class="sidebar" />

        </div>

        <!-- Main Layout -->
        <a-layout :style="{ height: '100vh', marginLeft: isMobile ? '0' : collapsed ? '0' : '200px' }">
            <!-- Header -->
            <a-layout-header class="header">
                <i class="fa" :class="collapsed ? 'fa fa-arrow-right' : ' fa fa-arrow-left'" @click.stop="collapsed = !collapsed"></i>
            </a-layout-header>

            <!-- Content -->
            <a-layout-content class="content">
                <slot />
            </a-layout-content>
        </a-layout>
    </a-layout>
</template>

<style scoped>
/* Header */
.header {
    background: #001529;
    padding: 0 20px;
    display: flex;
    align-items: center;
    color: white;
}

/* Trigger Button */
.fa {
    font-size: 18px;
    cursor: pointer;
    transition: color 0.3s;
    color: rgb(0, 0, 0);
}

/* Sidebar Wrapper */
.sidebar-wrapper {
    position: fixed;
    top: 0;
    left: 0;
    height: 100%;
    transition: all 0.3s ease;
}

/* Overlay Effect */
.overlay {
    width: 100vw;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1000;
}

/* Sidebar */
.sidebar {
    width: 200px;
    height: 100vh;
    background: #001529;
    z-index: 1100;
}
</style>
