<script setup lang="ts">
import { ref, watch, computed, defineProps } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { UserOutlined, DatabaseOutlined } from '@ant-design/icons-vue';

// Props
defineProps<{ collapsed: boolean }>();

// Selected menu key
const selectedKeys = ref<string[]>([]);

// Define menu items for better maintainability
const menuItems = [
  { key: '1', label: 'Dashboard', icon: DatabaseOutlined, route: 'user.dashboard' },
  { key: '2', label: 'Categories', icon: UserOutlined, route: 'user.categories' },
  { key: '3', label: 'Brands', icon: UserOutlined, route: 'user.brands' },
  { key: '4', label: 'Products', icon: UserOutlined, route: 'user.products' },
  { key: '5', label: 'Purchase Products', icon: UserOutlined, route: 'user.purchase.product.list' },
  { key: '6', label: 'Get Orders', icon: UserOutlined, route: 'user.order.create' },
  { key: '7', label: 'Order List', icon: UserOutlined, route: 'user.order.list' },
  { key: '8', label: 'Profile', icon: UserOutlined, route: 'profile.edit' },
];

// Compute selected key based on URL
const page = usePage();
const currentPath = computed(() => page.url);
watch(currentPath, (newUrl) => {
  const matchedItem = menuItems.find(item => newUrl.includes(item.route.split('.').pop() || ''));
  selectedKeys.value = matchedItem ? [matchedItem.key] : [];
}, { immediate: true });
</script>

<template>
  <a-layout-sider :collapsed="collapsed" :trigger="null" collapsible>
    <div class="logo">
      <h3 v-if="!collapsed">MyApp</h3>
    </div>
    <a-menu v-model:selectedKeys="selectedKeys" theme="dark" mode="inline">
      <a-menu-item v-for="item in menuItems" :key="item.key">
        <Link :href="route(item.route)">
          <component :is="item.icon" />
          <span>{{ item.label }}</span>
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
