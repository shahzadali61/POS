<script setup lang="ts">
import Sidebar from '@/Components/admin/Sidebar.vue';
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue';
import { MenuUnfoldOutlined, MenuFoldOutlined } from '@ant-design/icons-vue';

// Sidebar state
const collapsed = ref<boolean>(false);
</script>

<template>
    <Head title="Dahboard" />
  <a-layout>
    <!-- Sidebar Component (Now Using v-model) -->
    <Sidebar v-model:collapsed="collapsed" />

    <!-- Main Layout -->
    <a-layout :style="{ height: '100vh' }">
      <!-- Header -->
      <a-layout-header class="header">
        <menu-unfold-outlined v-if="collapsed" class="trigger" @click="collapsed = false" />
        <menu-fold-outlined v-else class="trigger" @click="collapsed = true" />
      </a-layout-header>

      <!-- Content Area -->
      <a-layout-content class="content">
        <slot /> <!-- Render Page Content Here -->
      </a-layout-content>
    </a-layout>
  </a-layout>
</template>

<style scoped>
.header {
  background: #fff;
  padding: 0 16px;
  display: flex;
  align-items: center;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.trigger {
  font-size: 18px;
  cursor: pointer;
  transition: color 0.3s;
  padding: 0 16px;
}

.trigger:hover {
  color: #1890ff;
}

.content {
  margin: 24px 16px;
  padding: 24px;
  background: #fafafa;
  height: 100vh;
  border-radius: 8px;
  overflow-y: auto;
}
</style>
