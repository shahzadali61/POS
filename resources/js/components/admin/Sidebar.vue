<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { UserOutlined, DatabaseOutlined } from '@ant-design/icons-vue';

const props = defineProps<{ collapsed: boolean }>();
const emit = defineEmits(['update:collapsed']);

</script>

<template>
  <a-layout-sider :collapsed="collapsed" :trigger="null" collapsible>
    <div class="logo">
      <h3 v-if="!collapsed">MyApp</h3>
    </div>
    <a-menu v-model:selectedKeys="selectedKeys" theme="dark" mode="inline">
      <a-menu-item key="1">
        <Link :href="route('user.dashboard')">
          <DatabaseOutlined />
          <span>Dashboard</span>
        </Link>
      </a-menu-item>
      <a-menu-item key="2">
        <Link :href="route('user.brands')">
          <UserOutlined />
          <span>Brands</span>
        </Link>
      </a-menu-item>
      <a-menu-item key="3">
        <Link :href="route('profile.edit')">
          <UserOutlined />
          <span>Profile</span>
        </Link>
      </a-menu-item>
    </a-menu>
  </a-layout-sider>
</template>

<style scoped>
.logo {
  height: 32px;
  background: rgba(255, 255, 255, 0.3);
  margin: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  color: white;
}
</style>
