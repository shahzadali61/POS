<script setup lang="ts">
import type { BreadcrumbItemType } from '@/types';
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

import {
  UserOutlined,
  UploadOutlined,
  MenuUnfoldOutlined,
  MenuFoldOutlined,
} from '@ant-design/icons-vue';

interface Props {
  breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
  breadcrumbs: () => [],
});

const selectedKeys = ref<string[]>(['1']);
const collapsed = ref<boolean>(false);

const toggleCollapse = () => {
  collapsed.value = !collapsed.value;
};
</script>

<template>
  <a-layout>
    <!-- Sidebar -->
    <a-layout-sider v-model:collapsed="collapsed" :trigger="null" collapsible>
      <div class="logo" />
      <a-menu v-model:selectedKeys="selectedKeys" theme="dark" mode="inline">
        <a-menu-item key="1">
            <Link :href="route('admin.dashboard')">
            <user-outlined />
            <span>Dashboard</span>
          </Link>
        </a-menu-item>
        <a-menu-item key="2">
            <Link :href="route('admin.brands')">
            <user-outlined />
            <span>Brands</span>
          </Link>
        </a-menu-item>
        <a-menu-item key="3">
          <upload-outlined />
          <span>Settings</span>
        </a-menu-item>
      </a-menu>
    </a-layout-sider>

    <!-- Main Layout -->
    <a-layout :style="{ height: '100vh' }">
      <!-- Header -->
      <a-layout-header class="header">
        <menu-unfold-outlined v-if="collapsed" class="trigger" @click="toggleCollapse" />
        <menu-fold-outlined v-else class="trigger" @click="toggleCollapse" />
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

.logo {
  height: 32px;
  background: rgba(255, 255, 255, 0.3);
  margin: 16px;
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
